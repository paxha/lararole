<?php

namespace Lararole\Http\Controllers;

use Lararole\Models\Module;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($module_slug)
    {
        $data = $this->data($module_slug);

        return view($data['view'].'.index', $data);
    }

    private function data($module_slug)
    {
        $module = Module::whereSlug($module_slug)->firstOrFail();

        $view = 'modules';

        foreach ($module->ancestors->reverse() as $ancestor) {
            $view .= '.'.$ancestor->slug;
        }

        $view .= '.'.$module->slug;

        $data['view'] = $view;
        $data['module'] = $module;

        return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($module_slug)
    {
        $data = $this->data($module_slug);

        return view($data['view'].'.create', $data);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($module_slug, $id)
    {
        $data = $this->data($module_slug);

        return view($data['view'].'.show', $data)->with('id', $id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($module_slug, $id)
    {
        $data = $this->data($module_slug);

        return view($data['view'].'.edit', $data)->with('id', $id);
    }

    public function access_denied()
    {
        return view('access_denied');
    }
}
