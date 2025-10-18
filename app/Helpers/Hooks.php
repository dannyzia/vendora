<?php

use App\Core\Hooks\HookManager;

/**
 * Add a filter hook
 */
if (!function_exists('add_filter')) {
    function add_filter(string $hook, callable $callback, int $priority = 10, int $acceptedArgs = 1): void
    {
        HookManager::addFilter($hook, $callback, $priority, $acceptedArgs);
    }
}

/**
 * Apply filters to a value
 */
if (!function_exists('apply_filters')) {
    function apply_filters(string $hook, $value, ...$args)
    {
        return HookManager::applyFilters($hook, $value, ...$args);
    }
}

/**
 * Add an action hook
 */
if (!function_exists('add_action')) {
    function add_action(string $hook, callable $callback, int $priority = 10, int $acceptedArgs = 1): void
    {
        HookManager::addAction($hook, $callback, $priority, $acceptedArgs);
    }
}

/**
 * Execute action hooks
 */
if (!function_exists('do_action')) {
    function do_action(string $hook, ...$args): void
    {
        HookManager::doAction($hook, ...$args);
    }
}

/**
 * Remove a filter
 */
if (!function_exists('remove_filter')) {
    function remove_filter(string $hook, callable $callback, int $priority = 10): bool
    {
        return HookManager::removeFilter($hook, $callback, $priority);
    }
}

/**
 * Remove an action
 */
if (!function_exists('remove_action')) {
    function remove_action(string $hook, callable $callback, int $priority = 10): bool
    {
        return HookManager::removeAction($hook, $callback, $priority);
    }
}

/**
 * Check if filter exists
 */
if (!function_exists('has_filter')) {
    function has_filter(string $hook, ?callable $callback = null): bool
    {
        return HookManager::hasFilter($hook, $callback);
    }
}

/**
 * Check if action exists
 */
if (!function_exists('has_action')) {
    function has_action(string $hook, ?callable $callback = null): bool
    {
        return HookManager::hasAction($hook, $callback);
    }
}

/**
 * Remove all filters for a hook
 */
if (!function_exists('remove_all_filters')) {
    function remove_all_filters(string $hook): void
    {
        HookManager::removeAllFilters($hook);
    }
}

/**
 * Remove all actions for a hook
 */
if (!function_exists('remove_all_actions')) {
    function remove_all_actions(string $hook): void
    {
        HookManager::removeAllActions($hook);
    }
}