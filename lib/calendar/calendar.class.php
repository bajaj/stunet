<?php
class Calendar
{
    private $year;
    private $month;
    private $day;
    private $startDay=0;
    private $days=array('Sun','Mon','Tue','Wed','Thur','Fri','Sat');
    private $months=array(
        0=>'',
        1=>'January',
        2=>'February',
        3=>'March',
        4=>'April',
        5=>'May',
        6=>'June',
        7=>'July',
        8=>'August',
        9=>'September',
        10=>'October',
        11=>'November',
        12=>'December');
    
    //Days of the week ordered by startDay
    private $orderedDays;
    
    //Name of the current month
    private $monthName;
    
    //Dates of the month
    private $dates=array();
    
    //Styles for each day of the month
    private $dateStyles=array();
    
    //List of days with events
    private $daysWithEvents=array();
    
    //Data to associate with dates
    private $data=array();
    
    //Data associated with dates, in corresponding 42 record array
    private $dateData=array();
    
    public function __construct($day,$month,$year)
    {
        $this->year=($year=='')?date('y'):$year;
        $this->month=($month=='')?date('m'):$month;
        $this->day=($day=='')?date('d'):$day;
        $this->monthName=$this->months[ltrim($this->month,'0')];//ltrim trims out 0 from the month name for example remove 0 from 07
    }
    
    public function buildMonth()
    {
        $this->orderedDays=$this->getDaysInOrder();
        $this->monthName=$this->months[ltrim($this->month,'0')];
        $start_of_month=getdate(mktime(12,0,0,$this->month,1,$this->year));//mktime retursn Unix timestamp, getdate returns an associative array from the given timestamp
        $first_day_of_month=$start_of_month['wday'];
        $days=$this->startDay-$first_day_of_month;
        if($days>1)
        {
            $days-=7;
        }
        $num_days=$this->daysInMonth($this->month,$this->year);
        $start=0;
        $cal_dates=array();
        $cal_dates_style=array();
        $cal_events=array();
        while($start<42)
        {
            if($days<0)
            {
                $cal_dates[]='';
                $cal_dates_style[]='calendar-empty';
                $cal_dates_data[]='';
            }
            else
            {
                if($days<$num_days)
                {
                    $cal_dates[]=$days+1;
                    if(in_array($days+1, $this->daysWithEvents))
                    {
                        $cal_dates_style[]='has-events';
                        $cal_dates_data[]=$this->data[$days+1];
                    }
                    else
                    {
                        $cal_dates_style[]='';
                        $cal_dates_data[]='';
                    }
                }
                else
                {
                    $cal_dates[]='';
                    $cal_dates_style[]='calendar-empty';
                    $cal_dates_data[]='';
                }
            }
            $days++;
            $start++;
        }
        $this->dates=$cal_dates;
        $this->dateData=$cal_dates_data;
        $this->dateStyles=$cal_dates_style;
    }
    
    function daysInMonth($m,$y)
    {
        if($m<1||$m>12)
        {
            return 0;
        }
        else
        {
            if($m==4||$m==6||$m==9||$m==11)
            {
                return 30;
            }
            elseif($m!=2)
            {
                return 31;
            }
            else
            {
                if($y%4!=0)
                {
                    return 28;
                }
                else
                {
                    if($y%100!=0)
                    {
                        return 29;
                    }
                    else
                    {
                        if($y%400!=0)
                        {
                            return 28;
                        }
                        else
                        {
                            return 29;
                        }
                    }
                }
            }
        }
    }
    
    function getDaysInOrder()
    {
        $ordered_days=array();
        for($i=0;$i<7;$i++)
        {
            $ordered_days[]=$this->days[($this->startDay+$i)%7];
        }
        return $ordered_days;
    }
    
    function getPreviousMonth()
    {
        $pm=new Calendar('',($this->month>1)?$this->month-1:12,($this->month>1)?$this->year:$this->year-1);
        return $pm;
    }
    
    function getNextMonth()
    {
        $nm=new Calendar('',($this->month==12)?1:$this->month+1,($this->month==12)?$this->year+1:$this->year);
        return $nm;
    }
    
    public function getMonth()
    {
        return $this->month;
    }
    
    public function getMonthName()
    {
        return $this->monthName;
    }
    
    public function getYear(){
        return $this->year;
    }
    
    public function setStartDay($start)
    {
        $this->startDay=$start;
    }
    
    public function setYear($y)
    {
        $this->year=$y;
    }
    
    public function setMonth($m)
    {
        $this->month=$m;
    }
    
    public function setDaysWithEvents($var)
    {
        $this->daysWithEvents=$var;
    }
    
    public function setData($data)
    {
        $this->data=$data;
    }
    
    public function getDates()
    {
        return $this->dates;
    }
    
    public function getDateData()
    {
        return $this->dateData;
    }
    
    public function getDateStyles()
    {
        return $this->dateStyles;
    }
    
    
    

    
}
?>