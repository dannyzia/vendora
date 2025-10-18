<?php

namespace App\Core\Hooks;

class HookManager
{
    /**
     * Registered filters (modify data)
     */
    protected static array $filters = [];
    
    /**
     * Registered actions (trigger events)
     */
    protected static array $actions = [];
    
    /**
     * Add a filter hook
     * 
     * @param string $hook Hook name
     * @param callable $callback Function to execute
     * @param int $priority Priority (lower = earlier)
     * @param int $acceptedArgs Number of arguments
     */
    public static function addFilter(string $hook, callable $callback, int $priority = 10, int $acceptedArgs = 1): void
    {
        self::$filters[$hook][$priority][] = [
            'callback' => $callback,
            'accepted_args' => $acceptedArgs
        ];
        
        // Sort by priority
        if (isset(self::$filters[$hook])) {
            ksort(self::$filters[$hook]);
        }
    }
    
    /**
     * Add an action hook
     * 
     * @param string $hook Hook name
     * @param callable $callback Function to execute
     * @param int $priority Priority (lower = earlier)
     * @param int $acceptedArgs Number of arguments
     */
    public static function addAction(string $hook, callable $callback, int $priority = 10, int $acceptedArgs = 1): void
    {
        self::$actions[$hook][$priority][] = [
            'callback' => $callback,
            'accepted_args' => $acceptedArgs
        ];
        
        // Sort by priority
        if (isset(self::$actions[$hook])) {
            ksort(self::$actions[$hook]);
        }
    }
    
    /**
     * Apply filters to a value
     * 
     * @param string $hook Hook name
     * @param mixed $value Value to filter
     * @param mixed ...$args Additional arguments
     * @return mixed Filtered value
     */
    public static function applyFilters(string $hook, $value, ...$args)
    {
        if (!isset(self::$filters[$hook])) {
            return $value;
        }
        
        foreach (self::$filters[$hook] as $priority => $callbacks) {
            foreach ($callbacks as $callback) {
                $callArgs = [$value];
                
                if ($callback['accepted_args'] > 1) {
                    $callArgs = array_merge($callArgs, array_slice($args, 0, $callback['accepted_args'] - 1));
                }
                
                $value = call_user_func_array($callback['callback'], $callArgs);
            }
        }
        
        return $value;
    }
    
    /**
     * Execute action hooks
     * 
     * @param string $hook Hook name
     * @param mixed ...$args Arguments to pass
     */
    public static function doAction(string $hook, ...$args): void
    {
        if (!isset(self::$actions[$hook])) {
            return;
        }
        
        foreach (self::$actions[$hook] as $priority => $callbacks) {
            foreach ($callbacks as $callback) {
                $callArgs = array_slice($args, 0, $callback['accepted_args']);
                call_user_func_array($callback['callback'], $callArgs);
            }
        }
    }
    
    /**
     * Remove a filter
     * 
     * @param string $hook Hook name
     * @param callable $callback Function to remove
     * @param int $priority Priority
     */
    public static function removeFilter(string $hook, callable $callback, int $priority = 10): bool
    {
        if (!isset(self::$filters[$hook][$priority])) {
            return false;
        }
        
        foreach (self::$filters[$hook][$priority] as $key => $filter) {
            if ($filter['callback'] === $callback) {
                unset(self::$filters[$hook][$priority][$key]);
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Remove an action
     * 
     * @param string $hook Hook name
     * @param callable $callback Function to remove
     * @param int $priority Priority
     */
    public static function removeAction(string $hook, callable $callback, int $priority = 10): bool
    {
        if (!isset(self::$actions[$hook][$priority])) {
            return false;
        }
        
        foreach (self::$actions[$hook][$priority] as $key => $action) {
            if ($action['callback'] === $callback) {
                unset(self::$actions[$hook][$priority][$key]);
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Check if filter exists
     */
    public static function hasFilter(string $hook, ?callable $callback = null): bool
    {
        if (!isset(self::$filters[$hook])) {
            return false;
        }
        
        if ($callback === null) {
            return true;
        }
        
        foreach (self::$filters[$hook] as $priority => $callbacks) {
            foreach ($callbacks as $filter) {
                if ($filter['callback'] === $callback) {
                    return true;
                }
            }
        }
        
        return false;
    }
    
    /**
     * Check if action exists
     */
    public static function hasAction(string $hook, ?callable $callback = null): bool
    {
        if (!isset(self::$actions[$hook])) {
            return false;
        }
        
        if ($callback === null) {
            return true;
        }
        
        foreach (self::$actions[$hook] as $priority => $callbacks) {
            foreach ($callbacks as $action) {
                if ($action['callback'] === $callback) {
                    return true;
                }
            }
        }
        
        return false;
    }
    
    /**
     * Remove all filters for a hook
     */
    public static function removeAllFilters(string $hook): void
    {
        if (isset(self::$filters[$hook])) {
            unset(self::$filters[$hook]);
        }
    }
    
    /**
     * Remove all actions for a hook
     */
    public static function removeAllActions(string $hook): void
    {
        if (isset(self::$actions[$hook])) {
            unset(self::$actions[$hook]);
        }
    }
    
    /**
     * Get all registered filters
     */
    public static function getFilters(): array
    {
        return self::$filters;
    }
    
    /**
     * Get all registered actions
     */
    public static function getActions(): array
    {
        return self::$actions;
    }
}