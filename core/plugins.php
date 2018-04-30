<?php
/*
 * Hook/listener system
 *
 * Allows plugins to hook into various listeners and change/modify content
 */

$listeners = array();

/*
 * Hook
 *
 * Gets all listeners and parses the respective functions
 *
 * @return string $args
 */
function hook() {
    global $listeners;

    $num_args = func_num_args();
    $args = func_get_args();

    if ((int) $num_args < 2) {
        trigger_error('Insufficient arguments. Hooks require 2 arguments to be present.', E_USER_ERROR);
    }

    /*
     * Hook name should always be first argument
     */
    $hook_name = array_shift($args);

    /*
     * Return if no plugin has registered this hook
     */
    if (!isset($listeners[$hook_name])) {
        return;
    }

    foreach ($listeners[$hook_name] as $func) {
        $args = $func($args); 
    }

    return $args;
}

/*
 * Listener
 *
 * Attaches a function to a hook
 *
 * @param string $hook
 * @param string $function_name
 */
function add_listener($hook, $function_name) {
    global $listeners;

    $listeners[$hook][] = $function_name;
}
