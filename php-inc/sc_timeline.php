<?

// sceniCode Time Line
// There is no composer integration, it follows no PSR rules etc.
// The output is returned by the methods in sc_timeline_output
//

/**
 * Class sc_timeline_event
 *
 * Each event (with its date, title, etc) is a sc_timeline_event
 */
class sc_timeline_event
{

    public $date;
    public $title;
    public $img_filename;
    public $desc;

    /**
     * events constructor.
     * @param $date
     * @param $title
     * @param $img_filename
     * @param $desc
     */
    public function __construct($date, $title, $img_filename, $desc)
    {
        $this->date = $date;
        $this->title = $title;
        $this->img_filename = $img_filename;
        $this->desc = $desc;
    }


    /**
     * @return mixed
     */
    public function date()
    {
        return $this->date;
    }

    /**
     * @return mixed
     */
    public function title()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function image()
    {
        if (isset($this->image) && $this->image) {
            $file = $this->image;
            return "<img src='$file' />";
        }
    }

    /**
     * @return string
     */
    public function desc()
    {
        return $this->desc;
    }

}


/**
 * Used for the categories for each timeline element.
 * Generally is used for years, so use sc_year
 */
interface sc_category
{
    function __construct($input);
    public function to_string();
}

/**
 * Class sc_year
 *
 * For the year variable, for the events timeline.
 * We don't automatically create it from sc_timeline_event::date, as it might not always be grouped by that var.
 * If, when creating this object, you do new sc_year("A title") it will trigger a notice as "A title" isn't four digits.
 * Instead use new sc_year("A title",FALSE) to disable checking the year is valid.
 */
class sc_year implements  sc_category
{
    /**
     * @var
     */
    private $year;

    /**
     * @param $year - e.g. 2009, 2011, etc
     * @param $valid_year_check - if true it will check if $year is 'valid' (only for recent years, it checks for four digits. Ancient years or years over 8000yrs in the future will trigger a notice too.
     */
    function __construct($year, $valid_year_check = true)
    {


        // show notice if it isn't a valid (recent) year
        if ($valid_year_check && !preg_match("/[0-9]{4}/", $year)) {
            trigger_error("sc_year \$year (" . htmlentities($year) . ") was not a valid year", E_USER_NOTICE);
        }
        $this->year = $year;
    }

    /**
     * @return string
     */
    public function to_string()
    {
        return (string)$this->year;
    }
}

/**
 * Class sc_timeline_list
 *
 * Holds all the items in the time line.
 * It would look something like this:
 *
 * $this->events is an array, with the keys being a sc_year object's to_string() value.
 * The items in that array are sc_timeline_event objects.
 *
 * )
 */
class sc_timeline_list
{

    /**
     * @var array
     */
    protected $events = array();

    /**
     * @param $year
     * @param $date
     * @param $title
     * @param $img_filename
     * @param $desc
     *
     *
     * helper function/wrapper for $this->add, that creates the sc_year and sc_timeline_event objects - uses sc_year for the sc_category.
     */
    public function adder($year, $date, $title, $img_filename, $desc)
    {
        $this->add(new sc_year($year), new sc_timeline_event($date, $title, $img_filename, $desc));
    }

    /**
     * @param sc_category $category
     * @param sc_timeline_event $event
     *
     * Add a new event (sc_timeline_event) $event, for $year
     */
    public function add(sc_category $category, sc_timeline_event $event)
    {

        // create the $this->events[2011] = array(), if it doesn't exist
        if (!isset($this->events[$category->to_string()])) {
            $this->events[$category->to_string()] = array();
        }

        $this->events[$category->to_string()][] = $event;

    }

    /**
     * @return array
     *
     * return the array of events ($this->events)
     */
    public function get_rows()
    {
        return $this->events;
    }

}

abstract class sc_timeline_output
{

    protected $timeline_list;

    function __construct(sc_timeline_list $timeline_list)
    {
        $this->timeline_list = $timeline_list;
    }



    /**
     * @param $year
     * @return string
     *
     * return a string from $year that is alphanumeric. on the end too to ensure its unique
     * We do the preg_replace because $year might not be a 4 digit number.
     */
    protected function get_element_id($year)
    {
        $year_str = "sc_". preg_replace("/([^0-9A-Za-z])/", "", $year) ;
        return $year_str;
    }



}

class sc_timeline_output_html extends sc_timeline_output
{



    public function table_of_contents()
    {
        $html = '';
        foreach ($this->timeline_list->get_rows() as $year => $events) {
            $html .= "<a href='#" . $this->get_element_id($year) . "'>$year</a> ";
        }
        return $html;
    }

    /**
     * @return string
     *
     * Output the timeline html. All the HTML is hard coded in here.
     * Make a new class that extends sc_timeline_output if you want to change it
     */
    public function timeline()
    {

        $html = "<div class='sc_timeline clearfix'>";
        $html .= "<ul>";
        /**
         * @var timeline_event $events
         * @var  $events
         */
        foreach ($this->timeline_list->get_rows() as $year => $events) {
            $year_str = $this->get_element_id($year);

            $html .= "<li class='sc_timestamp'><span id='$year_str' class='sc_anchor_ts'></span>$year</li>";
//                    quick hack because we started with odd/even
            $html .= "<li style='display:none;'></li>";

            foreach ($events as $event) {

                $html .= "<li class='sc_event'>";
                    $html .= "<div class='sc_eventdata'>";
                    $html .= "<div class='sc_titlemeta clearfix'>";
                    $html .= "<h3>{$event->title()}</h3>";
                    $html .= "<div class='sc_timelinedate'>{$event->date()}</div>";
                    $html .= "</div>"; // .sc_titlemeta
                    $html .= $event->image();
                    $html .= "<div class='sc_timelineevent_desc'>";
                    $html .= $event->desc();
                    $html .= "</div>"; //.sc_timelineevent_desc
                    $html .= "</div>"; //.sc_eventdata
                $html .= "</li>"; //.sc_event

            }

        }
        $html .= "</ul>";
        $html .= "</div>"; //.sc_timeline
        return $html;

    }

}
