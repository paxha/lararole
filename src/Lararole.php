<?php

namespace Lararole;

class Lararole
{
    /**
     * Indicates if Lararole's migrations will be run.
     *
     * @var bool
     */
    public static $runsMigrations = true;

    /**
     * Indicates if Lararole's routes will be run.
     *
     * @var bool
     */
    public static $runsRoutes = true;

    /**
     * Indicates if Lararole's views will be run.
     *
     * @var bool
     */
    public static $runsViews = true;

    /**
     * Determine if Lararole's migrations should be run.
     *
     * @return bool
     */
    public static function shouldRunMigrations()
    {
        return static::$runsMigrations;
    }

    /**
     * Determine if Lararole's migrations should be run.
     *
     * @return bool
     */
    public static function shouldRunRoutes()
    {
        return static::$runsRoutes;
    }

    /**
     * Determine if Lararole's views should be run.
     *
     * @return bool
     */
    public static function shouldRunViews()
    {
        return static::$runsViews;
    }

    /**
     * Configure Lararole to not register its migrations.
     *
     * @return static
     */
    public static function ignoreMigrations()
    {
        static::$runsMigrations = false;

        return new static;
    }

    /**
     * Configure Lararole to not register its routes.
     *
     * @return static
     */
    public static function ignoreRoutes()
    {
        static::$runsMigrations = false;

        return new static;
    }

    /**
     * Configure Lararole to not register its views.
     *
     * @return static
     */
    public static function ignoreViews()
    {
        static::$runsViews = false;

        return new static;
    }
}
