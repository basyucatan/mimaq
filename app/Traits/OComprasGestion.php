<?php

namespace App\Traits;
use App\Models\{Material, Materialscosto, Empresa, Obra};
trait OComprasGestion
{
    public function filtroMats()
    {
        if (strlen($this->keyWordMat) < 2) return [];
        $key = '%' . $this->keyWordMat . '%';
        return Materialscosto::with(['material.Unidad', 'color', 'Moneda', 'barra'])
            ->join('materials', 'materialscostos.IdMaterial', '=', 'materials.id')
            ->where(function ($query) use ($key) {
                $query->whereHas('material', function ($q) use ($key) {
                    $q->where('material', 'LIKE', $key)
                    ->orWhere('referencia', 'LIKE', $key);
                })
                ->orWhere('materialscostos.referencia', 'LIKE', $key);
            })
            ->orderBy('materials.material')
            ->select('materialscostos.*')
            ->limit(100)
            ->get();
    }
    public function filtroProvs()
    {
        if ($this->IdProveedor || strlen($this->keyWordProv) < 2) return [];
        return Empresa::where('tipo', 'proveedor')
            ->where('empresa', 'LIKE', '%' . $this->keyWordProv . '%')
            ->limit(10)->get();
    }
    public function filtroClientes()
    {
        if (strlen($this->keyWordCte) < 2) return [];
        return Empresa::where('tipo', 'cliente')->where('empresa', 'LIKE', '%' . $this->keyWordCte . '%')->limit(5)->get();
    }
    public function crearEmpresa()
    {
        $this->validate([
            'nuevaEmpresa.empresa' => 'required|unique:empresas,empresa',
            'nuevaEmpresa.telefono' => 'required|unique:empresas,telefono|digits:10',
            'nuevaEmpresa.email' => 'required|unique:empresas,email|email'
            ]);
        $emp = Empresa::create([
            'IdNegocio' => $this->nuevaEmpresa['IdNegocio'],
            'tipo' => 'proveedor',
            'empresa' => $this->nuevaEmpresa['empresa'],
            'direccion' => $this->nuevaEmpresa['direccion'],
            'telefono' => $this->nuevaEmpresa['telefono'],
            'email' => $this->nuevaEmpresa['email']
        ]);
        $this->elegirProv($emp->id, $emp->empresa);
        $this->resetNuevaEmpresa();
    }
    public function crearObra()
    {
        $this->validate(['nuevaObra.obra' => 'required', 'IdCliente' => 'required']);
        $obra = Obra::create([
            'IdEmpresa' => $this->IdCliente,
            'obra' => $this->nuevaObra['obra'],
            'gmaps' => $this->nuevaObra['gmaps']
        ]);
        $this->IdObra = $obra->id;
        $this->verNuevaObra = false;
        $this->nuevaObra = ['obra' => '', 'gmaps' => ''];
    }

    public function crearMaterial()
    {
        $this->validate([
            'nuevoMat.referencia' => 'required|unique:materials,referencia',
            'nuevoMat.material' => 'required|unique:materials,material',
            'nuevoMat.costo' => 'required|numeric|gt:0',
            'nuevoMat.IdMoneda' => 'required',
            'nuevoMat.IdClase' => 'required'
        ]);
        $material = Material::firstOrCreate(
            ['referencia' => mb_strtoupper($this->nuevoMat['referencia'])],
            ['material' => $this->nuevoMat['material'], 
            'IdUnidad' => $this->nuevoMat['IdUnidad'], 
            'IdClase' => $this->nuevoMat['IdClase'], 
            'IdLinea' => $this->nuevoMat['IdLinea'] ?: 20
            ]
        );
        $costo = Materialscosto::create([
            'IdMaterial' => $material->id,
            'referencia' => mb_strtoupper($this->nuevoMat['referencia']),
            'costo' => $this->nuevoMat['costo'],
            'IdMoneda' => $this->nuevoMat['IdMoneda'],
            'IdColor' => !empty($this->nuevoMat['IdColor']) ? $this->nuevoMat['IdColor'] : null
        ]);
        $this->elegirMaterial($costo->id);
        $this->verNuevoMat = false;
        $this->resetNuevoMat();
    }
}