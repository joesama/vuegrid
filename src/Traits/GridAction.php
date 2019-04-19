<?php

namespace Joesama\VueGrid\Traits;

/**
 * Handles Actions.
 *
 * @author joharijumali@gmail.com
 **/
trait GridAction
{
    /**
     * Check Either All Icons.
     **/
    public function checkingActions($actions)
    {
        $checkAction = collect([]);

        foreach ($actions as $action) {
            $act = collect();

            $keys = collect($action)->keys()->toArray();

            if (in_array('delete', $keys)) {
                $act->put('delete', __('joesama/vuegrid::datagrid.buttons.delete'));

                $act->put('icons', data_get($action, 'icons', 'fas fa-trash-alt'));

                $act->put('url', data_get($action, 'url'));

                $act->put('key', data_get($action, 'key'));
            } else {
                if (!in_array('icons', $keys)) {
                    $act->put('icons', 'far fa-question-circle');
                }

                foreach ($keys as $key) {
                    if ('icons' == $key) {
                        $act->put($key, data_get($action, $key, 'far fa-question-circle'));
                    } else {
                        $act->put($key, data_get($action, $key));
                    }
                }
            }

            $checkAction->push($act);
        }

        return $checkAction->toArray();
    }
} // END class
