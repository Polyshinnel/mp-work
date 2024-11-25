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

    public function getLabelById(int $labelId): ?SiteLabel
    {
        return SiteLabel::find($labelId);
    }

    public function getSiteLabelByLabelId(int $siteLabelId): ?SiteLabel
    {
        return SiteLabel::where('site_label_id', $siteLabelId)->first();
    }

    public function createSiteStatus(array $createArr): void
    {
        SiteStatusList::create($createArr);
    }

    public function getSiteStatusList(): Collection
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

    public function getSiteStatusById(int $statusId): ?SiteStatusList
    {
        return SiteStatusList::find($statusId);
    }

    public function getSiteStatusBySiteStatusId(int $siteStatusId): ?SiteStatusList
    {
        return SiteStatusList::where('site_status_id', $siteStatusId)->first();
    }
}
