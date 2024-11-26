<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OzonOrder extends Model
{
    use HasFactory;

    protected $guarded = false;

    public function siteStatus(): HasOne
    {
        return $this->hasOne(SiteStatusList::class, 'id', 'site_status_id');
    }

    public function siteLabel(): HasOne
    {
        return $this->hasOne(SiteLabel::class, 'id', 'site_label_id');
    }

    public function ozonWarehouse(): HasOne
    {
        return $this->hasOne(OzonWarehouse::class, 'id', 'ozon_warehouse_id');
    }

    public function siteInfo(): HasOne
    {
        return $this->hasOne(Site::class, 'id', 'site_id');
    }

    public function ozonStatus(): HasOne
    {
        return $this->hasOne(OzonStatusList::class, 'id', 'ozon_status_id');
    }
}
