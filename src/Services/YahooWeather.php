<?php
/**
 * Created by PhpStorm.
 * User: JIDAL_MOHAMED
 * Date: 12/06/2018
 * Time: 15:17
 */

namespace App\Services;


class YahooWeather
{


    /**
     * Constructor
     *
     * You have to supply a 'Yahoo! Where On Earth ID (WOEID)'.
     * If you do not supply a unit, degrees will be printed in Fahrenheit and all other attributes in English units.
     * For more information see {@link http://developer.yahoo.com/geo/geoplanet/guide/concepts.html}
     *
     * @param int $woeid The WOEID of the place, you want the weather information for. default = 2442047, which is the WOEID for Los Angeles, CA, USA.
     * @param string $unit default = 'f'. Use 'f' for °Fahrenheit or 'c' for °Celsius. **If you use Celsius, all other units will be metric units as well!**
     * @access public
     */
    public function __construct($code = "1532755")
    {
        // $this->woeid = $woeid;
        $this->unit = "c";

        $url = "http://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20weather.forecast%20where%20woeid%20=".$code."&format=json";

        $this->code = urlencode($code);

        /* Let's use cURL to query the API and get all the XML data */
        $curlobj = curl_init();
        curl_setopt($curlobj, CURLOPT_URL, $url);
        curl_setopt($curlobj, CURLOPT_RETURNTRANSFER, 1);
        $yahooapi = curl_exec($curlobj);
        curl_close($curlobj);

        /* Now let's create a weather object of the returned XML */
        // $this->weather = new \SimpleXMLElement($yahooapi);
        $oWeather = json_decode($yahooapi);
        $this->weather = $oWeather->query->results;

    }



    /**
     * Weather conditions
     */

    /**
     * temperature
     *
     * Use the $showunit boolean parameter to show or hide the specified unit.
     * Returns the current temperature.
     *
     * @param bool $showunit default = true, shows '°F' for Fahrenheit or '°C' for Celsius
     * @return string|int If the unit is shown, the function returns a string.
     * @access public
     */
    public function getTemperature($showunit = true)
    {
        $temperature = $this->fahrenheit2Celsius($this->weather->channel->item->condition->temp);
        if($showunit) $temperature .= '&#8239;&#176;'.$this->weather_unit[0]->attributes()->temperature;

        return $temperature;
    }


    /**
     * temperature unit
     *
     * Returns the plain temperature unit without numbers or symbols.
     *
     * @return object 'F' for Fahrenheit or 'C' for Celsius
     * @access public
     */
    public function getTemperatureUnit()
    {

        return $this->weather_unit[0]->attributes()->temperature;
    }


    /**
     * condition code
     *
     * Yahoo! provides a unique code for every possible weather condition.
     * You can use this e.g. for your own weather icons, condition translations, etc.
     *
     * @see http://developer.yahoo.com/weather/#codes
     * @return int The weather condition code, ranges from '0-47' or '3200' for 'not available'
     * @access public
     */
    public function getConditionCode()
    {

        return (int)$this->weather_condition[0]->attributes()->code;
    }


    /**
     * description of conditions
     *
     * Returns a textual description of conditions, for example, 'Sunny'.
     *
     * @return object e.g. 'Sunny'
     * @access public
     */
    public function getDescription()
    {
        return $this->weather->channel->item->condition->text;
    }



    /**
     * Location
     */

    /**
     * location city
     *
     * Returns the city of the current location.
     *
     * @return object city
     * @access public
     */
    public function getLocationCity()
    {

        return $this->weather_location[0]->attributes()->city;
    }


    /**
     * location country
     *
     * Returns the country of the current location.
     *
     * @return object country
     * @access public
     */
    public function getLocationCountry()
    {

        return $this->weather_location[0]->attributes()->country;
    }


    /**
     * location region
     *
     * Returns the region or territory of the current location, if given.
     *
     * @return object region, territory or state
     * @access public
     */
    public function getLocationRegion()
    {

        return $this->weather_location[0]->attributes()->region;
    }


    /**
     * location state
     *
     * Returns the state of the current location, if given.
     * Note: This gives the identical result as 'getLocationRegion()'!
     * Although Yahoo does not differentiate states, regions or territories in their results, this name might be more intuitive for use with U.S. states.
     *
     * @return object region, territory or state
     * @access public
     */
    public function getLocationState()
    {

        return $this->weather_location[0]->attributes()->region;
    }


    /**
     * latitude and longitude
     *
     * Returns the latitude and longitude of the location, e.g. '50.94, 6.96'
     *
     * @return string
     * @access public
     */
    public function getLocationLatLong()
    {

        return $this->weather->channel->item->lat.', '.$this->weather->channel->item->long;
    }



    /**
     * Wind
     */

    /**
     * wind speed
     *
     * Use the $showunit boolean parameter to show or hide the specified unit.
     * Use the $decimals integer parameter to set the number of decimals.
     * Use the $separator string parameter to set the separator character.
     * Returns the wind speed.
     *
     * @param bool $showunit default = true, shows 'mph' for miles per hour or 'km/h' for kilometers per hour
     * @param int $decimals default = 2, sets the number of decimals
     * @param string $separator default ='.', sets the separator character
     * @return string If the unit is shown, the function returns a string.
     * @access public
     */
    public function getWindSpeed($showunit = true, $decimals = 2, $separator = '.')
    {
        $speed = number_format(floatval($this->weather->channel->wind->speed * 1.609344), $decimals, $separator, '');
        if($showunit) $speed .= ' Km/h';

        return $speed;
    }


    /**
     * wind direction
     *
     * Use the $showunit boolean parameter to show or hide the specified unit.
     * Returns the wind direction in degrees
     *
     * @param bool $showunit default = true, shows '°'
     * @return string wind direction in degrees
     * @access public
     */
    public function getWindDirection($showunit = true)
    {
        $direction = $this->weather_wind[0]->attributes()->direction;
        if($showunit) $direction .= '&#176;';

        return $direction;
    }


    /**
     * Cardinal wind direction
     *
     * Returns the Cardinal wind direction, e.g. 'NNE' for 'North North-East'
     *
     * @return string direction
     * @access public
     */
    public function getWindDirectionCardinal()
    {

        return $this->WindDegreesToCardinal($this->weather_wind[0]->attributes()->direction);
    }


    /**
     * wind chill temperature
     *
     * Use the $showunit boolean parameter to show or hide the specified unit.
     * Returns the current wind chill temperature.
     * @see http://en.wikipedia.org/wiki/Wind_chill
     *
     * @param bool $showunit default = true, shows '°F' for Fahrenheit or '°C' for Celsius
     * @return string|int If the unit is shown, the function returns a string.
     * @access public
     */
    public function getWindChill($showunit = true)
    {
        $chill = (int)$this->weather_wind[0]->attributes()->chill;
        if($showunit) $chill .= '&#8239;&#176;'.$this->weather_unit[0]->attributes()->temperature;

        return $chill;
    }



    /**
     * Atmosphere
     */

    /**
     * humidity
     *
     * Use the $showunit boolean parameter to show or hide the specified unit.
     * Returns the relative humidity.
     *
     * @param bool $showunit default = true, shows '%' (percent)
     * @return string|int If the unit is shown, the function returns a string.
     * @access public
     */
    public function getHumidity($showunit = true)
    {
        $humidity = (int)$this->weather_atmosphere[0]->attributes()->humidity;
        if($showunit) $humidity .= '&#37;';

        return $humidity;
    }


    /**
     * barometric pressure
     *
     * Use the $showunit boolean parameter to show or hide the specified unit.
     * Returns the barometric pressure.
     *
     * @param bool $showunit default = true, shows 'in' for pounds per square inch or 'mb' for millibars
     * @return string|float If the unit is shown, the function returns a string.
     * @access public
     */
    public function getPressure($showunit = true)
    {
        $pressure = (float)$this->weather_atmosphere[0]->attributes()->pressure;
        if($showunit) $pressure .= ' '.$this->weather_unit[0]->attributes()->pressure;

        return $pressure;
    }


    /**
     * barometric pressure state
     *
     * Returns the state of the barometric pressure: steady (0), rising (1), or falling (2).
     *
     * @return int steady (0), rising (1), or falling (2)
     * @access public
     */
    public function getPressureState()
    {

        return (int)$this->weather_atmosphere[0]->attributes()->rising;
    }


    /**
     * visibility distance
     *
     * Use the $showunit boolean parameter to show or hide the specified unit.
     * Returns the visibility distance.
     *
     * @param bool $showunit default = true, shows 'mi' for miles or 'km' for kilometers
     * @return string|float If the unit is shown, the function returns a string.
     * @access public
     */
    public function getVisibility($showunit = true)
    {
        $visibility = (float)$this->weather_atmosphere[0]->attributes()->visibility;
        if($showunit) $visibility .= ' '.$this->weather_unit[0]->attributes()->distance;

        return $visibility;
    }



    /**
     * Astronomy
     */

    /**
     * sunrise time
     *
     * Returns the sunrise time at the given location.
     *
     * @param $time_format Style the output format of the time. Default is 'g:i a'
     * @see http://php.net/manual/en/function.date.php
     * @return string
     * @access public
     */
    public function getSunrise($time_format = 'g:i a')
    {
        $time = new DateTime($this->weather_astronomy[0]->attributes()->sunrise);

        return $time->format($time_format);
    }


    /**
     * sunset time
     *
     * Returns the sunset time at the given location.
     *
     * @param $time_format Style the output format of the time
     * @see http://php.net/manual/en/function.date.php
     * @return string
     * @access public
     */
    public function getSunset($time_format = 'g:i a')
    {
        $time = new DateTime($this->weather_astronomy[0]->attributes()->sunset);

        return $time->format($time_format);
    }



    /**
     * Service
     */

    /**
     * Yahoo! URL
     *
     * Yahoo!'s Terms of Use ask to provide attribution to Yahoo! Weather Service in connection with the use of their weather feed.
     * To do so, you can easily link back to Yahoo!'s weather service site, where you can also find a 5 day forecast for the current location.
     *
     * @return object The Yahoo! URL.
     * @access public
     */
    public function getYahooURL()
    {

        return $this->weather->channel->link;
    }


    /**
     * Yahoo! weather icon
     *
     * Returns the html image tag for the weather condition icon, which is used by Yahoo! and Weather.com
     * I don't know, if grabbing the image out of context is fine for Yahoo, so I suggest to use your own icons
     * and use this one for testing purposes only.
     *
     * @return string
     * @access public
     */
    public function getYahooIcon()
    {
        $weather_description = $this->weather->channel->item->description;
        preg_match_all('/<img[^>]+>/i',$weather_description, $icon);

        return $icon[0][0];
    }


    /**
     * last updated date
     *
     * Use the $date_format parameter to format the output of the date.
     * Returns the date, the weather information was last updated.
     *
     * @param string $date_format default = 'D, d M Y g:i a e', the date format defined by RFC822 Section 5, formats the output of the date
     * @see http://php.net/manual/de/function.date.php
     * @return string
     * @access public
     */
    public function getLastUpdated($date_format = 'D, d M Y g:i a e')
    {
        $date = new DateTime($this->weather->channel->item->pubDate);

        return $date->format($date_format);
    }


    /**
     * Time to Live
     *
     * Returns how long in minutes the weather data should be cached.
     *
     * @return int Time to Live, in minutes
     * @access public
     */
    public function getTTL()
    {

        return (int)$this->weather->channel->ttl;
    }



    /**
     * Forecast
     */

    /**
     * forecasted low temperature for today
     *
     * Use the $showunit boolean parameter to show or hide the specified unit.
     * Returns the forecasted low temperature for today.
     *
     * @param bool $showunit default = true, shows '°F' for Fahrenheit or '°C' for Celsius
     * @return string|int If the unit is shown, the function returns a string.
     * @access public
     */
    public function getForecastTodayLow($showunit = true)
    {
        $temperature = $this->weather_forecast[0]->attributes()->low;
        if($showunit) $temperature .= '&#8239;&#176;'.$this->weather_unit[0]->attributes()->temperature;

        return $temperature;
    }

    /**
     * forecasted high temperature for today
     *
     * Use the $showunit boolean parameter to show or hide the specified unit.
     * Returns the forecasted high temperature for today.
     *
     * @param bool $showunit default = true, shows '°F' for Fahrenheit or '°C' for Celsius
     * @return string|int If the unit is shown, the function returns a string.
     * @access public
     */
    public function getForecastTodayHigh($showunit = true)
    {
        $temperature = $this->weather_forecast[0]->attributes()->high;
        if($showunit) $temperature .= '&#8239;&#176;'.$this->weather_unit[0]->attributes()->temperature;

        return $temperature;
    }


    /**
     * forecast for today: condition code
     *
     * Yahoo! provides a unique code for every possible weather condition.
     * You can use this e.g. for your own weather icons, condition translations, etc.
     *
     * @see http://developer.yahoo.com/weather/#codes
     * @return int The weather condition code, ranges from '0-47' or '3200' for 'not available'
     * @access public
     */
    public function getForecastTodayConditionCode()
    {

        return (int)$this->weather_forecast[0]->attributes()->code;
    }


    /**
     * description of forecasted conditions for today
     *
     * Returns a textual description of conditions, for example, 'Sunny'.
     *
     * @return object e.g. 'Sunny'
     * @access public
     */
    public function getForecastTodayDescription()
    {

        return $this->weather_forecast[0]->attributes()->text;
    }


    /**
     * today date
     *
     * Use the $date_format parameter to format the output of the date.
     * Returns today's date, used in the forecast data.
     *
     * @param string $date_format default = 'd M Y', formats the output of the date
     * @see http://php.net/manual/de/function.date.php
     * @return string today's date
     * @access public
     */
    public function getForecastTodayDate($date_format = 'd M Y')
    {
        $date = new DateTime($this->weather_forecast[0]->attributes()->date);

        return $date->format($date_format);
    }


    /**
     * forecasted low temperature for tomorrow
     *
     * Use the $showunit boolean parameter to show or hide the specified unit.
     * Returns the forecasted low temperature for tomorrow.
     *
     * @param bool $showunit default = true, shows '°F' for Fahrenheit or '°C' for Celsius
     * @return string|int If the unit is shown, the function returns a string.
     * @access public
     */
    public function getForecastTomorrowLow($showunit = true)
    {
        $temperature = $this->weather_forecast[1]->attributes()->low;
        if($showunit) $temperature .= '&#8239;&#176;'.$this->weather_unit[0]->attributes()->temperature;

        return $temperature;
    }


    /**
     * forecasted high temperature for tomorrow
     *
     * Use the $showunit boolean parameter to show or hide the specified unit.
     * Returns the forecasted high temperature for tomorrow.
     *
     * @param bool $showunit default = true, shows '°F' for Fahrenheit or '°C' for Celsius
     * @return string|int If the unit is shown, the function returns a string.
     * @access public
     */
    public function getForecastTomorrowHigh($showunit = true)
    {
        $temperature = $this->weather_forecast[1]->attributes()->high;
        if($showunit) $temperature .= '&#8239;&#176;'.$this->weather_unit[0]->attributes()->temperature;

        return $temperature;
    }


    /**
     * forecast for tomorrow: condition code
     *
     * Yahoo! provides a unique code for every possible weather condition.
     * You can use this e.g. for your own weather icons, condition translations, etc.
     *
     * @see http://developer.yahoo.com/weather/#codes
     * @return int The weather condition code, ranges from '0-47' or '3200' for 'not available'
     * @access public
     */
    public function getForecastTomorrowConditionCode()
    {

        return (int)$this->weather_forecast[1]->attributes()->code;
    }


    /**
     * description of forecasted conditions for tomorrow
     *
     * Returns a textual description of conditions, for example, 'Sunny'.
     *
     * @return object e.g. 'Sunny'
     * @access public
     */
    public function getForecastTomorrowDescription()
    {

        return $this->weather_forecast[1]->attributes()->text;
    }


    /**
     * tomorrow date
     *
     * Use the $date_format parameter to format the output of the date.
     * Returns tomorrow's date, used in the forecast data.
     *
     * @param string $date_format default = 'd M Y', formats the output of the date
     * @see http://php.net/manual/de/function.date.php
     * @return string tomorrow's date
     * @access public
     */
    public function getForecastTomorrowDate($date_format = 'd M Y')
    {
        $date = new DateTime($this->weather_forecast[1]->attributes()->date);

        return $date->format($date_format);
    }

    public function getForecastDays(){

        $aDays = array();

        setlocale(LC_TIME, "fr_FR");

        foreach ($this->weather->channel->item->forecast as $key => $value) {

            if ( $key == 5 )
                return $aDays;

            $oDate = \DateTime::createFromFormat('j M Y', $value->date);
            $sDateFr = strftime("%d %B, %Y", strtotime( $oDate->format('d-m-Y')) );
            $aDays[$key]["day"] = self::translatDay($value->day);
            $aDays[$key]["low"] = $this->fahrenheit2Celsius($value->low);
            $aDays[$key]["high"] = $this->fahrenheit2Celsius($value->high);
            $aDays[$key]["text"] = (string)$value->text;
            $aDays[$key]["code"] = (string)$value->code;
            $aDays[$key]["date"] = utf8_encode($sDateFr);
        }

        return $aDays;
    }



    /**
     * private functions
     */

    /**
     * wind degree to Cardinal converter
     *
     * Converts degrees to the Cardinal wind direction, e.g. 'NNE' for 'North North-East'
     *
     * @param $degree the wind direction in degrees
     * @return string direction
     * @access private
     */
    private function WindDegreesToCardinal($degree)
    {
        if($degree == 0) $direction = '';
        if($degree >= 348.75 && $degree <=  11.25) $direction = 'N';
        if($degree >   11.25 && $degree <=  33.75) $direction = 'NNE';
        if($degree >   33.75 && $degree <=  56.25) $direction = 'NE';
        if($degree >   56.25 && $degree <=  78.75) $direction = 'ENE';
        if($degree >   78.75 && $degree <= 101.25) $direction = 'E';
        if($degree >  101.25 && $degree <= 123.75) $direction = 'ESE';
        if($degree >  123.75 && $degree <= 146.25) $direction = 'SE';
        if($degree >  146.25 && $degree <= 168.75) $direction = 'SSE';
        if($degree >  168.75 && $degree <= 191.25) $direction = 'S';
        if($degree >  191.25 && $degree <= 213.75) $direction = 'SSW';
        if($degree >  213.75 && $degree <= 236.25) $direction = 'SW';
        if($degree >  236.25 && $degree <= 258.75) $direction = 'WSW';
        if($degree >  258.75 && $degree <= 281.25) $direction = 'W';
        if($degree >  281.25 && $degree <= 303.75) $direction = 'WNW';
        if($degree >  303.75 && $degree <= 326.25) $direction = 'NW';
        if($degree >  326.25 && $degree <  348.75) $direction = 'NNW';

        return $direction;
    }

    private static function translatDay($sDay){

        switch (strtolower($sDay)) {
            case 'mon':
                $snDay = 'Lun.';
                break;
            case 'tue':
                $snDay = 'Mar.';
                break;
            case 'wed':
                $snDay = 'Mer.';
                break;
            case 'thu':
                $snDay = 'Jeu.';
                break;
            case 'fri':
                $snDay = 'Ven.';
                break;
            case 'sat':
                $snDay = 'Sam.';
                break;
            case 'sun':
                $snDay = 'Dim.';
                break;

            default:
                $snDay = 'NA.';
                break;
        }
        return $snDay;
    }

    private function fahrenheit2Celsius($temp){

        return intval( ( $temp - 32 ) * 5/9 );
    }

}