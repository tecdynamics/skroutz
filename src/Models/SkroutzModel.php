<?php

namespace Tec\Skroutz\Models;

use Arr;
use DOMDocument;
use Exception;
use Tec\Base\Models\BaseModel;
use Language;

/**
 *  ****************************************************************
 *  *** DO NOT ALTER OR REMOVE COPYRIGHT NOTICES OR THIS HEADER. ***
 *  ****************************************************************
 *  Copyright © 2022 TEC-Dynamics LTD <support@tecdynamics.org>.
 *  All rights reserved.
 *  This software contains confidential proprietary information belonging
 *  to Tec-Dynamics Software Limited. No part of this information may be used, reproduced,
 *  or stored without prior written consent of Tec-Dynamics Software Limited.
 * @Author    : Michail Fragkiskos
 * @Created at: 10/02/2022 at 10:32
 * @Interface     : scroutzModel
 * @Package   : tec
 */
class SkroutzModel extends BaseModel {
    protected $table = 'skroutz';
    /**
     * @var array
     */
    protected $fillable = [
        'product',
        'lang',
        'show',
    ];
    const  _SKROUTZPATH='products.xml';
    public $error='';

    private const _name = 'name';
    private const _description = 'description';
    private const _manufacturer = 'brand_id';
    private const _instock = 'stock_status';
    private const _image = 'image';
    private const _additional_imageurl = 'images';
    private const _mpn = 'sku';
    private const _quantity = 'quantity';
    private const _price_with_vat = 'price';
    private const _vat = 'tax_id';
    private const _weight = 'weight';
    private const _size = 'size';
    private const _availability = 'availability';
    private const _ean = 'ean';
    private const _color = 'color';
    private const _id = 'id';
    private const _url = 'url';
    private const _category = 'category';

    /**
     * @var string[]
     */
    public $skroutzields = [
        'id' => self::_id,
        'name' => self::_name,
        'description' => self::_description,
        'manufacturer' => self::_manufacturer,
        'instock' => self::_instock,
        'link' => self::_url,
        'image' => self::_image,
        'additional_imageurl' => self::_additional_imageurl,
        'quantity' => self::_quantity,
        'price_with_vat' => self::_price_with_vat,
        'vat' => self::_vat,
        'weight' => self::_weight,
        'size' => self::_size,
        'mpn' => self::_mpn,
        'availability' => self::_availability,
        'ean' => self::_ean,
        'color' => self::_color,
        'url' => self::_url,
        'category' => self::_category,

    ];


    public function build() {
        $skroutz_xml_selected_lang = setting('skroutz_xml_selected_lang', 'el');
        $scroutzModel = SkroutzModel::where('show', 1)->where('lang', $skroutz_xml_selected_lang)->get();

        try {
            @chmod(public_path(self::_SKROUTZPATH), 0777);
            $smodel = new SkroutzModel();
            $xml = new \DOMDocument('1.0', 'UTF-8');
            $xml->formatOutput = true;
            $xml->preserveWhiteSpace = false;
            $mywebstore = $xml->appendChild(
                $xml->createElement('mywebstore')
            );
            $mywebstore->appendChild(
                $xml->createElement('created_at', date('Y-m-d H:i:s'))
            );

            $products = $mywebstore->appendChild(
                $xml->createElement('products')
            );

            foreach ($scroutzModel as $skroutzproduct) {
                $product = get_product_by_id($skroutzproduct->product_id);
                $smodel->createXml($xml, $products->appendChild($xml->createElement('product')), $product);
            }
            \DB::table('settings')->updateOrInsert(['key' => 'skroutz_update_time'], ['value' => time()]);
           $xml->save(public_path(self::_SKROUTZPATH));
            return @chmod(public_path(self::_SKROUTZPATH), 0644);
        }
        catch (Exception $e) {
            // handle the error
            $this->error = $e->getMessage();
            return false;
        }
        $this->error = 'Error please contact your Admin';
        return false;
    }

    /**
     * @Function   saveSkroutzData
     * @param $method
     * @param $request
     * @param $data
     * @Author    : Michail Fragkiskos
     * @Created at: 10/02/2022 at 20:55
     * @param $method
     * @param $request
     * @param $data
     * @return false|void
     */
    public function saveSkroutzData($method, $request, $data) {
        if ($method != 'product') return false;
        $skroutz = $request->input('skroutz', 0);
        $lang = $request->input('skroutz_lang', Language::getCurrentAdminLocaleCode());
        $row = ['product_id' => $data->id, 'show' => (int)$skroutz, 'lang' => $lang];
        SkroutzModel::updateOrInsert(['product_id' => $data->id], $row);
    }

    /**
     * @Function   updateSkroutzData
     * @param $method
     * @param $request
     * @param $data
     * @Author    : Michail Fragkiskos
     * @Created at: 10/02/2022 at 20:55
     * @param $method
     * @param $request
     * @param $data
     * @return false|void
     */
    public function updateSkroutzData($method, $request, $data) {
        if ($method != 'product') return false;
        $skroutz = $request->input('skroutz', 0);
        $lang = $request->input('skroutz_lang', Language::getCurrentAdminLocaleCode());
        $row = ['product_id' => $data->id, 'show' => (int)$skroutz, 'lang' => $lang];
        SkroutzModel::updateOrInsert(['product_id' => $data->id], $row);
    }

    /*#attributes: array:33 [▼
        "id" => 24
        "name" => "Signature Wood-Fired Mushroom and Caramelized"
        "description" => "<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline.</p><ul><li>1 Year  ▶"
        "content" => "<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is compl ▶"
        "status" => "published"
        "images" => "["products\/24.jpg","products\/24-1.jpg"]"
        "sku" => "HS-125-A0"
        "order" => 0
        "quantity" => 17
        "allow_checkout_when_out_of_stock" => 0
        "with_storehouse_management" => 1
        "is_featured" => 1
        "brand_id" => 5
        "is_variation" => 0
        "sale_type" => 0
        "price" => 110.0
        "sale_price" => 89.1
        "start_date" => null
        "end_date" => null
        "length" => 17.0
        "wide" => 20.0
        "height" => 18.0
        "weight" => 626.0
        "created_at" => "2022-01-04 21:14:17"
        "updated_at" => "2022-02-10 12:36:47"
        "tax_id" => 1
        "views" => 90061
        "stock_status" => "in_stock"
        "store_id" => 2
        "created_by_id" => 0
        "created_by_type" => "Tec\ACL\Models\User"
        "approved_by" => 0
        "image" => "products/24.jpg"
      ]*/
    public function createXml($xml, $productelement, $product) {
        $skroutz_availability = setting('skroutz_availability', 0);
        $skroutz_availability_preorder = setting('skroutz_availability_preorder', 0);
        $skroutz_xml_noavailability = setting('skroutz_xml_noavailability', 0);
        $skroutz_xml_desc_field = setting('skroutz_xml_desc_field', 'short');
        $skroutz_xml_selected_lang = setting('skroutz_xml_selected_lang', 'el');
        //product translation
       $translation = $product->translations->where('lang_code', $skroutz_xml_selected_lang)->first();
        foreach ($this->skroutzields as $skroutzKey => $productVal) {
            if ($product->{$productVal} == null && $skroutzKey != 'category') continue;
            switch ($skroutzKey) {
                case 'category':
                    if ($category = $product->categories->sortByDesc('id')) {
                        $el = $productelement->appendChild($xml->createElement($skroutzKey));
                        $cat = [];
                        foreach ($category as $c) {
                            $cat[] = $c->name;
                        }
                        $el->appendChild($xml->createCDATASection(implode('>', $cat)));
                    }
                    break;
                case 'id':
                    $productelement->appendChild($xml->createElement($skroutzKey, $product->{$productVal}));
                    break;
                case  'mpn':
                    $productelement->appendChild($xml->createElement($skroutzKey, $product->{$productVal}));
                    break;
                case  'instock':
                    if (!$product->isOutOfStock()) {
                        $productelement->appendChild($xml->createElement($skroutzKey, 'Y'));

                        $el = $productelement->appendChild($xml->createElement('availability'));
                        $el->appendChild($xml->createCDATASection($skroutz_availability));
                    } else {
                        $productelement->appendChild($xml->createElement($skroutzKey, 'N'));
                        $el = $productelement->appendChild($xml->createElement('availability'));
                        $el->appendChild($xml->createCDATASection($skroutz_xml_noavailability));
                    }
                    break;
                case 'vat':
                    $el = $productelement->appendChild($xml->createElement($skroutzKey));
                    $el->appendChild($xml->createTextNode($product->tax->percentage ?? 0));
                    break;
                case 'manufacturer':
                    $el = $productelement->appendChild($xml->createElement($skroutzKey));
                    $el->appendChild($xml->createTextNode($product->brand->name ?? ''));
                    break;
                case 'price_with_vat':
                    $el = $productelement->appendChild($xml->createElement($skroutzKey));
                    if ($product->front_sale_price !== $product->price) {
                        $el->appendChild($xml->createCDATASection($product->front_sale_price_with_taxes ?? 0));
                    } else {
                        $el->appendChild($xml->createCDATASection($product->price_with_taxes ?? 0));
                    }
                    break;
                case 'image':
                    $img = \RvMedia::getImageUrl(Arr::get($product->images, 1, $product->image), 'product-thumb', false, \RvMedia::getDefaultImage());
                    $el = $productelement->appendChild($xml->createElement($skroutzKey));
                    $el->appendChild($xml->createCDATASection($img));
                    break;
                case 'additional_imageurl':
                    $img = \RvMedia::getImageUrl($product->image, 'product-thumb', false, \RvMedia::getDefaultImage());
                    $el = $productelement->appendChild($xml->createElement($skroutzKey));
                    $el->appendChild($xml->createCDATASection($img));
                    break;
                case 'link':
                    $el = $productelement->appendChild($xml->createElement($skroutzKey));
                    $el->appendChild($xml->createCDATASection($product->url));
                    break;
                case 'url':
                    $el = $productelement->appendChild($xml->createElement($skroutzKey));
                    $el->appendChild($xml->createCDATASection($product->url));
                    break;
                case 'name':
                    $el = $productelement->appendChild($xml->createElement($skroutzKey));
                    $txt = $translation->name ?? $product->{$productVal};
                    $el->appendChild($xml->createCDATASection($txt));
                    break;
                case 'description':
                    $el = $productelement->appendChild($xml->createElement($skroutzKey));
                    $txt= $translation->description??$product->{$productVal};
                    $el->appendChild($xml->createCDATASection($txt));
                    break;
                default:
                    $el = $productelement->appendChild($xml->createElement($skroutzKey));
                    $el->appendChild($xml->createCDATASection($product->{$productVal}));
                    break;
            }
        }
    }

}
