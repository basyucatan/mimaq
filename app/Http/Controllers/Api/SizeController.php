<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;
class SizeController extends Controller
{
    public function index()
    {
        return response()->json(Size::all(), 200);
    }
    public function store(Request $request)
    {
        $request->validate([
            'size' => 'required',
        ]);
        $nuevoSize = Size::create($request->all());
        return response()->json($nuevoSize, 201);
    }
    public function show($id)
    {
        $registroSize = Size::find($id);
        if (!$registroSize) {
            return response()->json(['message' => 'Registro no encontrado'], 404);
        }
        return response()->json($registroSize, 200);
    }
    public function update(Request $request, $id)
    {
        $registroSize = Size::find($id);
        if (!$registroSize) {
            return response()->json(['message' => 'Registro no encontrado'], 404);
        }
        $request->validate([
            'size' => 'required',
        ]);
        $registroSize->update($request->all());
        return response()->json($registroSize, 200);
    }
    public function destroy($id)
    {
        $registroSize = Size::find($id);
        if (!$registroSize) {
            return response()->json(['message' => 'Registro no encontrado'], 404);
        }
        $registroSize->delete();
        return response()->json(null, 204);
    }
}