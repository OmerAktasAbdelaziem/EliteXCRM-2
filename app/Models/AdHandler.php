<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdHandler extends Model
{
    use HasFactory;

    protected $table = 'ad_handler';

    protected $fillable = [
        'pipeline_id',
        'sheet_name',
        'sheet_url',
        'sheet_country'
    ];

    public function pipeline()
    {
        return $this->belongsTo(Pipeline::class);
    }

    public function fields()
    {
        return $this->hasMany(AdHandlerField::class);
    }

    public function getSheetXlsxUrlAttribute()
    {
        $url = $this->sheet_url;

        preg_match('/\/d\/(.*?)\//', $url, $matches);
        preg_match('/gid=(\d+)/', $url, $gidMatch);

        $sheetId = $matches[1] ?? null;
        $gid = $gidMatch[1] ?? 0;

        return $sheetId
            ? "https://docs.google.com/spreadsheets/d/{$sheetId}/export?format=xlsx&gid={$gid}"
            : null;
    }

}