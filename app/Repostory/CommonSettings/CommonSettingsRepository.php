<?php

namespace App\Repostory\CommonSettings;

use App\Models\SiteLabel;
use App\Models\SiteStatusList;
use Illuminate\Database\Eloquent\Collection;

class CommonSettingsRepository
{
    public function createSiteLabel(array $createArr): void
    {
        SiteLabel::create($createArr);
    }

    public function getSiteLabels(): Collection
    {
        return SiteLabel::all();
    }

    public function updateSiteLabel(array $updateArr, int $labelId): void
    {
        SiteLabel::where('id', $labelId)->update($updateArr);
    }

    public function deleteSiteLabel(int $labelId): void
    {
        SiteLabel::where('id', $labelId)->delete();
    }

    public function createSiteStatus(array $createArr): void
    {
        SiteStatusList::create($createArr);
    }

    public function getSiteStatus(): Collection
    {
        return SiteStatusList::all();
    }

    public function updateSiteStatus(array $updateArr, int $statusId): void
    {
        SiteStatusList::where('id', $statusId)->update($updateArr);
    }

    public function deleteSiteStatus(int $statusId): void
    {
        SiteStatusList::where('id', $statusId)->delete();
    }
}
