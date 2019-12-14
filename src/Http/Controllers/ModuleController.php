<?php

namespace Lararole\Http\Controllers;

use Lararole\Models\Module;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $moduleSlug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($moduleSlug)
    {
        $data = $this->data($moduleSlug);

        return view($data['view'].'.index', $data);
    }

    /**
     * Making views directory and  finding Module.
     *
     * @param $moduleSlug
     * @return mixed
     */
    private function data($moduleSlug)
    {
        $module = Module::whereSlug($moduleSlug)->firstOrFail();

        $view = 'modules.';

        foreach (array_reverse($module->ancestors()->toArray()) as $ancestor) {
            $view .= $ancestor['slug'].'.';
        }

        $view .= $module->slug;

        $data['view'] = $view;
        $data['module'] = $module;

        return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $moduleSlug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($moduleSlug)
    {
        $data = $this->data($moduleSlug);

        return view($data['view'].'.create', $data);
    }

    /**
     * Display the specified resource.
     *
     * @param $moduleSlug
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($moduleSlug, $id)
    {
        $data = $this->data($moduleSlug);

        return view($data['view'].'.show', $data)->with('id', $id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $moduleSlug
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($moduleSlug, $id)
    {
        $data = $this->data($moduleSlug);

        return view($data['view'].'.edit', $data)->with('id', $id);
    }

    public function accessDenied()
    {
        return view('access_denied');
    }
}
