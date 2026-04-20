<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Size;
use Livewire\Attributes\Computed;
use App\Models\Util;
use Illuminate\Support\Facades\DB;

class Sizes extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $verModalSize=false, $selected_id, $keyWord, $size;
	
    public function updatedKeyWord()
	{
		$this->resetPage();
	}
    #[Computed]
	public function filteredSizes()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Size::Where('id','>',0)
			->where(function ($query) use ($keyWord) {
				$query
						->orWhere('size', 'LIKE', $keyWord);
			})
			->paginate(12);
	}

	public function render()
	{
		return view('livewire.sizes.view', [
			'sizes' => $this->filteredSizes,
		]);
	}
	
    public function cancel()
    {
        $this->resetInput();
        $this->verModalSize = false;
    }

    public function resetInput()
    {
        $this->reset();
    }

    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Size::findOrFail($id)->toArray());
        $this->verModalSize = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalSize = true;
    }    
    public function save()
    {
        $this->validate([
		'size' => 'required',
        ]);

        Size::updateOrCreate(
			['id' => $this->selected_id],
			[
				'size' => $this-> size
			]
		);
        $this->resetInput();
        $this->verModalSize = false;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) {
            Size::where('id', $id)->delete();
        }
    }
}