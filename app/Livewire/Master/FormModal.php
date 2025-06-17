<?php

namespace App\Livewire\Master;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithFileUploads;

class FormModal extends Component
{
    use WithFileUploads;

    public $id, $model, $table, $action = '', $title = '', $description = '', $formWidth = '', $fieldWidth = null;
    public $fields = [], $fieldDatas = [], $fieldActives = [];
    public $listeners = ['create','edit','save','delete','deleteFile','updateInputMap','updateInputData'];

    public function mount($setting)
    {
        $this->model = $setting['model'];
        $this->fields = $setting['fields'];
        $this->title = $setting['title'];
        $this->description = $setting['description'];
        $this->formWidth = $setting['formWidth'] ?? 'lg';
        $this->fieldWidth = $setting['fieldWidth'] ?? 12;
        $this->table = (new $this->model())->getTable();
    }

    protected function rules()
    {
        $rules = [
            'fieldDatas.name' => 'required|string|max:255',
            'fieldDatas.email' => 'required|email|max:255|unique:users,email' . ($this->id ? ',' . $this->id : '')
        ];
        if($this->action === 'create')
            $rules['fieldDatas.password'] = 'required|min:6';

        return $rules;
    }

    public function create()
    {
        $this->resetForm('create');
        $this->action = 'create';
    }

    public function updateInputMap($key, $value)
    {
        $this->fieldDatas[$key] = $value;
    }

    public function updateInputData($key, $value)
    {
        $this->fieldDatas[$key] = $value;
    }

    public function save()
    {
        $rules = [];
        $processedData = [];

        // Validasi dan proses data
        foreach ($this->fieldActives as $key => $field) {
            if (isset($field['rule'])){
                if (str_contains($field['rule'], 'unique'))
                {
                    $rules[$key] = implode('|', array_map(function ($rule) {
                        return str_contains($rule, 'unique')
                            ? $rule . ($this->id ? ',' . $this->id : '')
                            : $rule;
                    }, explode('|', $field['rule'])));
                }else
                {
                    if(isset($this->fieldDatas[$key . 'path']) && $field['type'] == 'file' && empty($this->fieldDatas[$key]))
                        continue;
                    $rules[$key] = $field['rule'];
                }
            }

            if (isset($field['processor'])) {
                $processedData[$key] = call_user_func($field['processor'], $this->fieldDatas[$key]);
            }else {
                $processedData[$key] = $this->fieldDatas[$key];
            }
        }

        $validator = Validator::make($this->fieldDatas, $rules);
        if ($validator->fails()) {
            foreach ($validator->errors()->messages() as $key => $messages) {
                foreach ($messages as $message) {
                    $this->addError($key, $message);
                }
            }
            return;
        }
        $fileFields = array_filter($this->fields, function ($item) {
            return $item['type'] == 'file';
        });
        foreach ($fileFields as $key => $field) {
            if(isset($this->fieldDatas[$key])){
                try {
                    $filename = time() . '-' . uniqid() . '.' . $this->fieldDatas[$key]->getClientOriginalExtension();

                    $processedData[$key] = $this->fieldDatas[$key]->storeAs('uploads/' . $this->table . '/' . $key, $filename, 'public');
                } catch (\Exception $e) {
                    $this->dispatch('error', 'Gagal mengunggah file. Silakan coba lagi.');
                    return;
                }
            }
        }

        if ($this->action == 'edit') {
            $data = $this->model::findOrFail($this->id);
            $data->update($processedData);
            $this->dispatch('success', 'Data ' . $this->title . ' Behasil Diubah!');
        } else {
            $this->model::create($processedData);
            $this->dispatch('success', 'Data ' . $this->title . ' Behasil Dibuat!');
        }
    }

    public function edit($id)
    {
        $this->resetForm('edit');
        $data = $this->model::findOrFail($id);
        $this->id = $data->id;
        foreach ($this->fieldActives as $key => $item){
            if($item['type'] === 'file')
                $this->fieldDatas[$key . 'path'] = $data->$key;
            else
                $this->fieldDatas[$key] = $data->$key;
        }
        $this->action = 'edit';
    }

    public function delete($id)
    {
        $data = $this->model::findOrFail($id);

        $fileFields = array_filter($this->fields, function ($item) {
            return $item['type'] == 'file';
        });

        foreach ($fileFields as $key => $field) {
            $fileFields[$key]['path'] = $data->$key;
        }

        $data->delete();
        foreach ($fileFields as $key => $field) {
            if(Storage::disk('public')->exists($data->$key))
                Storage::disk('public')->delete($data->$key);
        }

        $this->dispatch('success', 'Data ' . $this->title . ' Behasil Dihapus!');
    }

    public function deleteFile($id)
    {
        $data = $this->model::findOrFail($this->id);
        if(Storage::disk('public')->exists($data->$id))
            Storage::disk('public')->delete($data->$id);
        $data->update([$id => null]);

        $this->dispatch('success', 'File ' . $id . ' Behasil Dihapus!');
    }

    public function resetForm($action = 'create')
    {
        $this->setFieldActives($action);
        $this->action = '';
        $this->id = null;
    }

    public function setFieldActives($action = 'create'){
        $this->fieldActives = [];
        foreach ($this->fields as $key => $item) {
            if($action == 'edit' && (!isset($item['isEdit']) || $item['isEdit'] !== false)){
                $this->fieldActives[$key] = $item;
                $this->fieldDatas[$key] = null;
            }elseif($action == 'create' && (!isset($item['isCreate']) || $item['isCreate'] !== false)){
                $this->fieldActives[$key] = $item;
                $this->fieldDatas[$key] = null;
            }
        }
    }

    public function render()
    {
        return view('components.master.form-modal');
    }
}
