<?php
    class Calendar {
        private $active_year, $active_month, $active_day , $my_date;
        private $events = [];
        
        public function __construct($date = null) {
            $this->active_year  = $date != null ? date('Y', strtotime($date)) : date('Y');
            $this->active_month = $date != null ? date('m', strtotime($date)) : date('m');
            $this->active_day   = $date != null ? date('d', strtotime($date)) : date('d');
        }
        
        public function add_event($txt, $date, $days = 1, $color = '') {
            $color = $color ? ' ' . $color : $color;
            $this->events[] = [$txt, $date, $days, $color];
        }
        public function __toString() {
            $num_days = date('t', strtotime($this->active_day . '-' . $this->active_month . '-' . $this->active_year));
            $num_days_last_month = date('j', strtotime('last day of previous month', strtotime($this->active_day . '-' . $this->active_month . '-' . $this->active_year)));
            $days = [
                0 => 'Fri',
                1 => 'Sat',
                2 => 'Sun',
                3 => 'Mon',
                4 => 'Tue',
                5 => 'Wed',
                6 => 'Thu',
            ];
            $daysAr = [
                0 => 'الجمعة',
                1 => 'السبت',
                2 => 'الاحد', 
                3 => 'الاثنين', 
                4 => 'الثلاثاء',
                5 => 'الاربعاء',
                6 => 'الخميس',
            ];
            $first_day_of_week = array_search(date('D', strtotime($this->active_year . '-' . $this->active_month . '-1')), $days);
            $nowDate = date('Y-m', strtotime($this->active_year . '-' . $this->active_month . '-' . $this->active_day));
            $html  = "<div class='calendar'>";//1
            $html .= "<div class='header'>";//2
            $html .= "<div class='row month-year'>";//3
            $html .= "<div class='col-2 mt-2'>"."<a href='?do=2&&date=$nowDate'>"."<i class='fa fa-angle-right'style='font-size:50px'></i>"."</a>"."</div>";
            $html .= "<div class='col-3 mt-2'>"."</div>";
            $html .= "<div class='col-2 mt-2'>".$nowDate."</div>";
            $html .= "<div class='col-4 mt-2'>"."</div>";
            $html .= "<div class='col-1 mt-2'>"."<a href='?do=1&&date=$nowDate'>"."<i class='fa fa-angle-left'style='font-size:50px'></i>"."</a>"."</div>";
            $html .= "</div>";//3
            $html .= "</div>";//2
            $html .= "<div class='days'>";//2
            foreach ($daysAr as $day) {
                $html .= "<div class='day_name'>" . $day . "</div>";//3//3
            }
            for ($i = $first_day_of_week; $i > 0; $i--) {
                $html .= '<div class="day_num ignore">'.'</div>';//3//3
            }
            for ($i = 1; $i <= $num_days; $i++) {
                $selected = '';
                if ($i == date('d') && $this->active_month == date('m') && $this->active_year == date('Y')){
                    $selected = ' selected';
                }
                $my_date = date('y-m-d', strtotime($this->active_year . '-' . $this->active_month.'-'.$i));
                $html .= '<div class="day_num' . $selected . '">';//3
                if ($this->active_year < date('Y')){
                    $html .= '<span>'.$i.'</span>';//
                }
                elseif($this->active_month < date('m') && $this->active_year == date('Y')){
                    $html .= '<span>'.$i.'</span>';//
                }
                // elseif($i < date('d') && $this->active_month == date('m') && $this->active_year == date('Y')){
                //     $html .= '<span>'.$i.'</span>';//
                // }
                else{
                    $html .= '<span>'."<a href='bocking.php?date=$my_date'>". $i."</a>".'</span>';//
                }
                foreach ($this->events as $event) {
                    for ($d = 0; $d <= ($event[2]-1); $d++) {
                        if (date('y-m-d', strtotime($this->active_year . '-' . $this->active_month . '-' . $i . ' -' . $d . ' day')) == date('y-m-d', strtotime($event[1]))) {
                            $html .= '<div class="event' . $event[3] . '">';
                            $html .= $event[0];
                            $html .= '</div>';
                        }
                    }
                }
                $html .= '</div>';
            }
            for ($i = 1; $i <= (35-$num_days-max($first_day_of_week, 0)); $i++) {
                $html .= '<div class="day_num ignore">'.'</div>';
            }
            $html .= '</div>';
            $html .= '</div>';
            return $html;
        }
    }
?>