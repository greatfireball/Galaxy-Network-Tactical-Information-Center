<?php
/*************************************************************************************
 *                                                                                   *
 *  T.I.C. NG - Next Generation of Tactical Information Center                       *
 *  Copyright (C) 2006  Tobias Sarnowski  <sarnowski@new-thoughts.org>               *
 *                                                                                   *
 *  This program is free software; you can redistribute it and/or                    *
 *  modify it under the terms of the GNU General Public License                      *
 *  as published by the Free Software Foundation; either version 2                   *
 *  of the License, or (at your option) any later version.                           *
 *                                                                                   *
 *  This program is distributed in the hope that it will be useful,                  *
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of                   *
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the                    *
 *  GNU General Public License for more details.                                     *
 *                                                                                   *
 *  You should have received a copy of the GNU General Public License                *
 *  along with this program; if not, write to the Free Software                      *
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.  *
 *                                                                                   *
 *************************************************************************************/

//
// Class Ticker
//
//

// --------------------------------------------------------------------------------- //

/* ftok() workaround for windows from http://de.php.net/ftok
 * david dot rech at virusmedia dot de
 * 27-May-2004 04:50
 */
if( !function_exists('ftok') ) {
    function ftok($filename = "", $proj = "")
    {
        if(empty($filename) || !file_exists($filename)) {
           return -1;
        } else {
            $filename = $filename . (string) $proj;
            for($key = array(); sizeof($key) < strlen($filename); $key[] = ord(substr($filename, sizeof($key), 1)));
            return dechex(array_sum($key));
        }
    }
}


class Ticker extends TICModule
{
    private $_thisTick = 0;
    private $_frequenceTick = 15;
    
    function Ticker()
    {
	parent::__construct(
	array(new Author("Tobias Sarnowski", "NataS", "sarnowski@new-thoughts.org")),
	"5",
	"Ticker",
	"Führt alle Ticks aus",
	array(
            "Core" => "4"
        ));
    
    }

    function onLoad()
    {
        global $tic;
        $this->_frequenceTick = $tic->mod['Core']->get($this->getName(), 'TickFrequency', $this->_frequenceTick);
        $this->_thisTick = $tic->mod['Core']->get($this->getName(), '_thisTick', $this->getGNTick() - 1);
    }
    
    function onPostLoad()
    {
        global $tic;

        $key = ftok(__FILE__, 'T');
        $sem = sem_get($key, 1);
        if ($sem === false)
            die("ERROR: can't get semaphore");

        $this->_lock($sem); // start critical section

        $actualTick = $this->getGNTick();
        $tick_counter = 0;
        if ($actualTick > $this->_thisTick) {
            $lastState = ignore_user_abort(true);
            set_time_limit(60);
            $ticks_to_execute = $actualTick - $this->_thisTick;
            //$tic->mod['Debug']->info($this->getName(), 'Found tick difference. '.$ticks_to_execute.' ticks to execute (frequency is '.$this->_frequenceTick.' minutes). Starting process ...');
            $startTime = $tic->mod['Core']->microtime_float();

            while ($actualTick > $this->_thisTick) {
                $this->_thisTick++;
                $this->_execTick($this->_thisTick);
                $tick_counter++;
            }

            $endTime = $tic->mod['Core']->microtime_float();
            $neededTime = $endTime - $startTime;
            //$tic->mod['Debug']->info($this->getName(), $ticks_to_execute.' ticks executed in '.$neededTime.' seconds.');
            set_time_limit(30);
            $tic->mod['Core']->set($this->getName(), '_thisTick', $this->_thisTick);
            ignore_user_abort($lastState);
        }

        $this->_unlock($sem); // end critical section

        $this->setVar('ticks_done', $tick_counter);
        $aktTick = $this->getTickTime($this->getThisTick());
        $this->setVar('thisTick', date("H:i:s", $aktTick));
    }
    
    function getThisTick()
    {
        return $this->_thisTick;
    }

    function setTickFrequency($minutes)
    {
        global $tic;
        $this->_frequenceTick = $minutes;
        $tic->mod['Core']->set($this->getName(), 'TickFrequency', $this->_frequenceTick);
        $tic->mod['Core']->set($this->getName(), '_thisTick', $this->getGNTick());
    }

    function getTickFrequency()
    {
        return $this->_frequenceTick;
    }
    
    function getGNTick()
    {
        return $this->getTick(time());
    }

    function getTick($timestamp)
    {
        return intval($timestamp / ($this->_frequenceTick * 60));
    }

    function getTickTime($tick)
    {
        return $tick * ($this->_frequenceTick * 60);
    }

    function _execTick($tick)
    {
        global $tic;
        $modlist = $tic->modListLoaded();
        //$tic->mod['Debug']->info($this->getName(), 'Executing tick '.$tick.' {'.implode(' ', $modlist).'} ...');
        for ($n = 0; $n < count($modlist); $n++)
            $tic->mod[$modlist[$n]]->onTick($tick);
    }

    function _lock($sem)
    {
            if (!sem_acquire($sem))
            die("ERROR: can't acquire semaphore");
    }
    
    function _unlock($sem)
    {
        if (!sem_release($sem))
            die("ERROR: can't release semaphore");
    }
    
    function execInstall($installdata)
    {
        if (isset($installdata['TickFrequency']))
            $this->setTickFrequency($installdata['TickFrequency']);
        else
            $this->setTickFrequency($this->getTickFrequency());
    }
    
    function getInstallQueriesMySQL() { return array(); }
    function getInstallQueriesPostgreSQL() { return array(); }
}

?>
