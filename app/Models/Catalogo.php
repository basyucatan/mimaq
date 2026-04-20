<?php
namespace App\Models;
class Catalogo
{
    protected static function path()
    {
        return base_path('settings.json');
    }
    public static function all($catalogo)
    {
        $data = self::read();
        if ($catalogo === null) return collect($data);
        return collect($data[$catalogo] ?? []);
    }
    public static function find($catalogo, $id)
    {
        return self::all($catalogo)->firstWhere('id', $id);
    }
    public static function saveItem($catalogo, $item)
    {
        $data = self::read();
        $items = collect($data[$catalogo] ?? []);
        if (isset($item['id']) && $item['id'] !== null) {
            $items = $items->map(function ($i) use ($item) {
                return $i['id'] == $item['id'] ? $item : $i;
            });
        } else {
            $item['id'] = ($items->max('id') ?? 0) + 1;
            $items->push($item);
        }
        $data[$catalogo] = $items->values()->toArray();
        self::write($data);
        return $item;
    }
    public static function delete($catalogo, $id)
    {
        $data = self::read();
        $data[$catalogo] = collect($data[$catalogo] ?? [])->where('id', '!=', $id)->values()->toArray();
        self::write($data);
    }
    protected static function read()
    {
        if (!file_exists(self::path())) return [];
        $json = file_get_contents(self::path());
        return json_decode($json, true) ?? [];
    }
    protected static function write($data)
    {
        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $json = str_replace('],"', "],\n\n  \"", $json);
        $json = str_replace('[{', "[\n    {", $json);
        $json = str_replace('},{', "},\n    {", $json);
        $json = str_replace('}]', "}\n  ]", $json);
        $json = "{\n  " . substr($json, 1, -1) . "\n}";
        file_put_contents(self::path(), $json);
        config(['settings.catalogos' => $data]);
    }
}