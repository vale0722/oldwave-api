<?php

namespace App\Actions;

use App\Helpers\FilesHelper;
use App\Models\Image;
use App\Models\Item;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\File\File;

class StoreOrUpdateItemAction extends Action
{
    public function execute(): self
    {
        if (!$this->getModel()) {
            $this->setModel(new Item());
        }

        $this->model->name = $this->data['name'];
        $this->model->slug = $this->data['slug'];
        $this->model->brand = $this->data['brand'];
        $this->model->city = $this->data['city'];
        $this->model->price = $this->data['price'];
        $this->model->currency = $this->data['currency'];
        $this->model->description = $this->data['description'];
        $this->model->discount = $this->data['discount'];
        $this->model->stock = $this->data['stock'];
        $this->model->category_id = $this->data['category'];
        $this->model->seller_id = $this->data['seller'];

        /** @var array $images */
        $images = Arr::get($this->data, 'images');

        /** @var File $image */
        $image = $images[0];

        $this->model->tumpnail = FilesHelper::resizeImg(
            'items/tumbnails/' . $image->hashName(),
            $image
        );

        $this->model->save();

        foreach ($images as $image) {
            $file = FilesHelper::save('items', $image);
            $modelImage = new Image();
            $modelImage->url = $file;
            $modelImage->item_id = $this->model->getKey();
            $modelImage->save();
        }
        return $this;
    }
}
