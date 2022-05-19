<?php

namespace Tec\Skroutz\Http\Controllers;

use Assets;
use Illuminate\Http\Request;
use Language;
use Tec\Base\Http\Controllers\BaseController;
use Tec\Base\Http\Responses\BaseHttpResponse;
use Tec\Base\Traits\HasDeleteManyItemsTrait;
use Tec\Skroutz\Models\SkroutzModel;

class SkroutzController extends BaseController {
    use HasDeleteManyItemsTrait;

    public $path = '';


    /**
     * SkroutzController constructor.
     */
    public function __construct() {
        $this->path = SkroutzModel::_SKROUTZPATH;

    }

    /**
     * @param NewsletterTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function index(BaseHttpResponse $response) {
        $data = [];
        $data['form_url'] = route('skroutz.savesettings');
        page_title()->setTitle(trans('plugins/skroutz::skroutz.name'));
        Assets::addStylesDirectly(['css/vendors/bootstrap.min.css'])
            ->addStylesDirectly(['css/vendors/uicons-regular-straight.css'])
            ->addScriptsDirectly([
                'vendor/core/plugins/ecommerce/js/edit-product.js',
            ]);
        $skroutz_last_update_time = setting('skroutz_last_update_time', time());
        $data['last_run'] = $skroutz_last_update_time;
        $data['xml_file'] = url($this->path);

        return view('plugins/skroutz::index', $data)->render();
    }

    /**
     * @Function   saveform
     * @param Request $request
     * @Author    : Michail Fragkiskos
     * @Created at: 10/02/2022 at 11:22
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveform(Request $request) {
        foreach ($request->input() as $key => $val) {
            if ($key == '_token') continue;
            \DB::table('settings')->updateOrInsert(['key' => $key], ['value' => $val]);
        }
        return back();
    }


    /**
     * @Function   runManual
     * @param Request $request
     * @Author    : Michail Fragkiskos
     * @Created at: 10/02/2022 at 11:22
     * @param Request $request
     * @return false|void
     */
    public function runManual(Request $request) {
        if (setting('skroutz_enable', 0) < 1) {
            $this->error = trans('core/base::forms.name');
            return false;
        }
        $SkroutzModel = new SkroutzModel();
        $SkroutzModel->build();
        return back();
    }

    /**
     * @Function   doMetaBoxes
     * @param $page
     * @param $data
     * @Author    : Michail Fragkiskos
     * @Created at: 10/02/2022 at 11:23
     * @param $page
     * @param $data
     */
    public function doMetaBoxes($page, $data) {

        if (!is_in_admin() || $page != 'advanced' ||
            (strpos($_SERVER['REQUEST_URI'], 'products') < 1)) return '';
        $html = '<div class="widget meta-boxes" >';
        $html .= '<div class="widget-title" ><h4 ><label for="skroutz" class="control-label">' . trans('plugins/skroutz::skroutz.product_skroutz_Availability') . '</label ></h4></div >';
        $html .= '<div class="widget-body"> <div class="row">';
        $html .= '<div class="col-sm-3"><input type="radio" name="skroutz" value="1" id="skroutz" class="styled" ';
        $scroutzModel = SkroutzModel::where('product_id', $data->id ?? 0)->first();

        if ($scroutzModel != null && (int)$scroutzModel->show > 0) {
            $html .= 'checked="checked" ';
        }
        $html .= '/>';
        $html .= '<label for="skroutz">' . trans('plugins/skroutz::skroutz.skroutz_availabile_yes') . '</label></div>';
        $html .= '<div class="col-sm-3"><input type="radio" name="skroutz" value="0" id="skroutz" class="styled" ';

        if ($scroutzModel != null && (int)$scroutzModel->show < 1) {
            $html .= 'checked="checked" ';
        }

        $html .= '/>';
        $html .= '<label for="skroutz">' . trans('plugins/skroutz::skroutz.skroutz_availabile_no') . '</label></div>';
        if (count(Language::getSupportedLocales()) > 0) {
            $html .= '<div class="row">';
            $html .= '<div class="form-group"> ';
            $html .= '<label for="skroutz_lang">' . trans('plugins/skroutz::skroutz.skroutz_lang') . '</label> ';
            $html .= '<select id="skroutz_lang" name="skroutz_lang" class="form-control">';
            foreach (Language::getSupportedLocales() as $lang) {
                $html .= '<option  ';
                if ($scroutzModel != null && $scroutzModel->lang == $lang['lang_code']) {
                    $html .= 'selected="selected" ';
                }
                $html .= ' value="' . $lang['lang_code'] . '">' . $lang['lang_name'] . '</option>';
            }
            $html .= ' </select> ';
            $html .= '</div> ';
            $html .= ' </div>';
        }
        $html .= '</div></div></div>';
        echo $html;
    }

}
