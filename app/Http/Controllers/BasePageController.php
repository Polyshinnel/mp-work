<?php

namespace App\Http\Controllers;


use App\Models\Page;
use App\Models\User;
use Illuminate\Http\Request;

class BasePageController extends Controller
{
    public function getBasePageInfo(Request $request): array
    {
        $pageUrl = $request->path();
        $pageInfo = Page::where('url', $pageUrl)->first();
        if($pageInfo) {
            $user = User::find(session('user_id'));
            $breadcrumbs = $this->generateBreadcrumbs($pageInfo);
            return [
                'breadcrumbs' => $breadcrumbs,
                'page_title' => $pageInfo->title,
                'block_title' => $pageInfo->block_title,
                'username' => $user->name,
            ];
        }
        return [];
    }

    private function generateBreadcrumbs(Page $page): array
    {
        $breadcrumbs = [];
        $parentId = $page->parent_id;
        $whileFlag = true;

        if($parentId == 0) {
            $breadcrumbs[] = [
                'name' => $page->title,
                'link' => '/'.$page->url,
                'active' => false
            ];
        } else {
            $pageInfo = $page;

            while($whileFlag) {
                if($pageInfo->parent_id == 0) {
                    $whileFlag = false;
                }
                $breadcrumbs[] = [
                    'name' => $pageInfo->title,
                    'link' => '/'.$pageInfo->url,
                    'active' => true
                ];
                $pageInfo = Page::find($pageInfo->parent_id);

            }

            $breadcrumbs = array_reverse($breadcrumbs);
        }
        return $breadcrumbs;
    }
}
