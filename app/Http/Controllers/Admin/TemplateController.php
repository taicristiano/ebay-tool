<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TemplateRequest;
use App\Models\SettingTemplate;
use Illuminate\Http\Request;

class TemplateController extends AbstractController
{
    public function __construct(SettingTemplate $template)
    {
        $this->template = $template;
    }

    /**
     * list shipping action
     * @return view
     */
    public function index(Request $req)
    {
        $templates = $this->template->getTemplateList($req->user()->id);
        return $this->render(compact('templates'));
    }

    /**
     * create shipping action
     * @return view|redirect
     */
    public function create(TemplateRequest $req, $templateId = null)
    {
        if ($req->isMethod('GET')) {
            if ($templateId) {
                $template = $this->template->findOrFail($templateId);
            }
            return $this->render(compact('template'));
        }
        $data            = $req->only($this->template->getFieldList());
        $data['user_id'] = $req->user()->id;
        $this->template->updateOrCreate(['id' => $templateId], $data);
        return redirect()->route('admin.template.index')->with([
            'message' => __('message.' . ($templateId ? 'updated' : 'created') . '_template_success'),
        ]);
    }

    /**
     * delete shipping action
     * @return redirect
     */
    public function delete($templateId)
    {
        $this->template->findOrFail($templateId)->delete();
        return redirect()->route('admin.template.index')->with([
            'message' => __('message.delete_template_success'),
        ]);
    }
}
