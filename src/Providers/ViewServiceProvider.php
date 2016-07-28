<?php

namespace Christhompsontldr\Laraboard\Providers;

use Illuminate\Support\ServiceProvider;
use Blade;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
        * Build data for the sidebar block.
        */
        view()->composer('*', function($view) {
            $view->with('messaging', \Config::has('messenger'));

            //  convert all times to user
            if (\Auth::check() && is_string(config('laraboard.user.timezone')) && !empty(\Auth::user()->{config('laraboard.user.timezone')})) {
                config('app.timezone', \Auth::user()->{config('laraboard.user.timezone')});
            }
        });

        Blade::directive('laraboard_date', function($expression) {
            //Split expression into two argument, using first comma found as the separator. Strip out the parenthesis for ease of use. If you require using parenthesis in your format argument, you'll want to change this to only remove the first and last characters of the expression string.
            $segments = explode(',', $expression, 2);

            //Get the date variable, trim whitespace just incase.
            $date = with(trim($segments[0]));

            $output = "<?php ";

            //Check if the date variable is empty and if the timestamp is valid (e.g. not '0000-00-00 00:00:00')
            $output .= "echo (! empty({$date}) and !is_null({$date}) and {$date}->timestamp > 0) ? ";

            $timezone = '';
            if (\Auth::check()) {
//                $timezone = '->setTimezone("' . \Auth::user()->timezone . '")';
            }

            //Then add it to the output.
            $output .= "{$date}" . $timezone . "->format('F j, Y')";

            //If the date didn't pass the empty and valid check above, output an empty string.
            $output .= " : ''; ?>";

            return $output;
        });

        Blade::directive('laraboard_date_short', function($expression) {
            //Split expression into two argument, using first comma found as the separator. Strip out the parenthesis for ease of use. If you require using parenthesis in your format argument, you'll want to change this to only remove the first and last characters of the expression string.
            $segments = explode(',', $expression, 2);

            //Get the date variable, trim whitespace just incase.
            $date = with(trim($segments[0]));

            $output = "<?php ";

            //Check if the date variable is empty and if the timestamp is valid (e.g. not '0000-00-00 00:00:00')
            $output .= "echo (! empty({$date}) and !is_null({$date}) and {$date}->timestamp > 0) ? ";

            $timezone = '';
            if (\Auth::check()) {
//                $timezone = '->setTimezone("' . \Auth::user()->timezone . '")';
            }

            //Then add it to the output.
            $output .= "{$date}" . $timezone . "->format('M j')";

            //If the date didn't pass the empty and valid check above, output an empty string.
            $output .= " : ''; ?>";

            return $output;
        });

        Blade::directive('laraboard_time', function($expression) {
            //Split expression into two argument, using first comma found as the separator. Strip out the parenthesis for ease of use. If you require using parenthesis in your format argument, you'll want to change this to only remove the first and last characters of the expression string.
            $segments = explode(',', $expression, 2);

            //Get the date variable, trim whitespace just incase.
            $date = with(trim($segments[0]));

            $output = "<?php ";

            //Check if the date variable is empty and if the timestamp is valid (e.g. not '0000-00-00 00:00:00')
            $output .= "echo (! empty({$date}) and !is_null({$date}) and {$date} != '00:00:00' and {$date} != '0000-00-00 00:00:00') ? ";

            $timezone = '';
            if (\Auth::check()) {
//                $timezone = '->setTimezone("' . \Auth::user()->timezone . '")';
            }

            //Then add it to the output.
            $output .= '\Carbon\Carbon' . "::parse({$date})" . $timezone . "->format('G:is') . ' <sup>' . " . '\Carbon\Carbon' . "::parse({$date})" . $timezone . "->format('T') . '</sup>'";

            //If the date didn't pass the empty and valid check above, output an empty string.
            $output .= " : ''; ?>";

            return $output;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
