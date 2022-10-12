<?php

namespace App\Console\Commands;

use App\Entities\Cashbox\Models\Cashbox;
use App\Entities\Category\Models\Category;
use App\Entities\Shop\Models\Shop;
use App\Entities\Subcategory\Models\Subcategory;
use App\Entities\Tag\Models\Tag;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SyncCrm extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:crm';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronizes shops with CRM';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Start');

        $categoriesSlug = [
            'Магазины' => 'shops',
            'Развлечения' => 'entertainment',
            'Услуги и сервисы' => 'services',
            'Еда' => 'food',
            'Банк' => 'bank',
            'Кино' => 'cinema',
            'Спорт' => 'sport',
            'Для детей' => 'kids',
        ];

        $shopsCrm = Http::get('https://rivierus.ru/api/objects?password=36WaE763f4mS6fcZ');
        $shopsCrm = $shopsCrm->json();
        $customShops = [];
        $shopsCrmIds = [];
        $tagsSearchRemove = [];
        $categoriesDefault = [];

        foreach ($shopsCrm as $shopCrm) {
            $customShop = [];
            $customShop['crm_id'] = $shopCrm['id'];
            $shopsCrmIds[] = $shopCrm['id'];
            $customShop['name'] = html_entity_decode($shopCrm['name'], ENT_QUOTES);
            $customShop['name_translate'] = html_entity_decode($shopCrm['name_en'], ENT_QUOTES);
            $customShop['floor'] = intval($shopCrm['level']) ?? 0;
            $customShop['show_in_home_display'] = $shopCrm['show_on_main'];
            $customShop['site_url'] = $shopCrm['website'];
            $customShop['cashback'] = $shopCrm['cashback'];
            $customShop['inn'] = $shopCrm['tax_number'];
            $customShop['description'] = htmlspecialchars_decode(html_entity_decode($shopCrm['description'], ENT_QUOTES), ENT_QUOTES | ENT_HTML5);
            $customShop['sitemap'] = $shopCrm['map'];
            $customShop['map'] = $shopCrm['map'];
            $customShop['map'] = $customShop['map'] ? $customShop['map'] . '&disabled_menu=true' : $customShop['map'];
            $customShop['search_tags'] = $shopCrm['search_tags'];
            $customShop['cashboxes'] = $shopCrm['cashboxes'];
            $customShop['logo_url'] = $shopCrm['logo_url'];
            $customShop['images'] = $shopCrm['images'];

            $customShop['category'] = $shopCrm['category'];
            $customShops[] = $customShop;
        }

        try {
            foreach ($customShops as $customShop) {
                $cashboxes = $customShop['cashboxes'];
                $tags = $customShop['search_tags'];
                $subcategoryIds = [];
                $tagIds = [];
                $logo = $customShop['logo_url'];
                $images = $customShop['images'];
                $categoryIds = null;

                if ($customShop['category']) {
                    foreach ($customShop['category'] as $categoryParent) {
                        $categoryName = !is_null($categoryParent['parent_category']) ? $categoryParent['parent_category'] : $categoryParent['name'];
                        $category = Category::where('name', $categoryName)->first();
                        if (!$category && isset($categoriesSlug[$categoryName])) {
                            $category = Category::create(
                                [
                                    'name' => $categoryName,
                                    'slug' => $categoriesSlug[$categoryName]
                                ]
                            );
                        }
                        $categoryIds[] = $category->id;

                        if ($categoryParent['name'] && $categoryParent['name'] !== $categoryParent['parent_category'] && !is_null($categoryParent['parent_category'])) {
                            $subcategory = Subcategory::where('name', $categoryParent['name'])->first();
                            if (!$subcategory) {
                                $subcategory = Subcategory::create(
                                    [
                                        'name' => $categoryParent['name']
                                    ]
                                );
                            }

                            $subcategoryIds[] = $subcategory->id;
                            $categoriesDefault[$category->id][] = $subcategory->id;
                        }
                    }
                }

                if ($tags) {
                    foreach ($tags as $tagName) {
                        $tag = Tag::where('name', $tagName)->first();
                        if (!$tag) {
                            $tag = Tag::create(
                                [
                                    'name' => $tagName
                                ]
                            );
                        }

                        $tagIds[] = $tag->id;
                        $tagsSearchRemove[] = $tag->id;
                    }
                }

                unset(
                    $customShop['category'],
                    $customShop['cashboxes'],
                    $customShop['search_tags'],
                    $customShop['logo_url'],
                    $customShop['images'],
                );

                $shop = Shop::where('crm_id', $customShop['crm_id'])->first();
                if ($shop) {
                    $shop->update($customShop);
                } else {
                    $shop = Shop::create($customShop);
                }
                $shop->subcategories()->sync($subcategoryIds);
                $shop->categories()->sync($categoryIds);
                $shop->tags()->sync($tagIds);
                $shop->clearMediaCollection('thumbnail');
                $shop->clearMediaCollection('images');

                if ($logo) {
                    $shop->addMediaFromUrl('https://rivierus.ru' . $logo)->toMediaCollection('thumbnail');
                }

                if ($images) {
                    foreach ($images as $image) {
                        $shop->addMediaFromUrl('https://rivierus.ru' . $image['url'])->toMediaCollection('images');
                    }
                }

                if ($cashboxes) {
                    $cashboxIdsNow = [];
                    foreach ($cashboxes as $cashbox) {
                        $cashboxIdsNow[] = $cashbox['cashbox_id'];
                        $cashboxObject = Cashbox::where('number_cahsbox', $cashbox['cashbox_id'])->first();
                        if (!$cashboxObject) {
                            Cashbox::create(
                                [
                                    'number_cahsbox' => $cashbox['cashbox_id'],
                                    'shop_id' => $shop->id
                                ]
                            );
                        } elseif ($cashboxObject->shop_id !== $shop->id) {
                            $cashboxObject->shop_id = $shop->id;
                            $cashboxObject->work = true;
                            $cashboxObject->save();
                        } else {
                            $cashboxObject->work = true;
                            $cashboxObject->save();
                        }
                    }

                    $cashboxes = Cashbox::where('shop_id', $shop->id)->whereNotIn('number_cahsbox', $cashboxIdsNow)->get();
                    foreach ($cashboxes as $cashbox) {
                        $cashbox->update(['work' => false]);
                    }
                }
            }

            Shop::whereNotIn('crm_id', $shopsCrmIds)->delete();

            foreach ($categoriesDefault as $categoryId => $subcategories) {
                $category = Category::find($categoryId);
                $category->subcategories()->sync($subcategories);
            }

        } catch (\Exception $exception) {
            file_put_contents(
                storage_path('/log-sync-' . date('Y-m-d') . '.txt'),
                date('Y-m-d H:i:s') . ' Error: ' . json_encode($exception) . "\n",
                FILE_APPEND
            );

            $this->error($exception->getMessage() . ' ' . $exception->getLine());
        }

        $this->info('End');
    }
}
