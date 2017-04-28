<?php

namespace OCA\Calendar\Controller;

use OCP\AppFramework\Controller;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\NotFoundResponse;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\IRequest;
use OCP\IDBConnection;
use OCP\IConfig;

class OneTimeLinkController extends Controller {

  /**
   * @var IDBConnection
   */
  private $db;

  /**
   * @var IConfig
   */
  private $config;

  /**
   * @param string $appName
   * @param IRequest $request an instance of the request
   */
  public function __construct($appName, IRequest $request, IDBConnection $db,
								IConfig $config) {
    parent::__construct($appName, $request);
    $this->db = $db;
    $this->config = $config;
  }

  /**
  * @param string $url
  * @param string uri
  * @param string name
	* @PublicPage
	* @NoCSRFRequired
  * @return int
	*/
	public function reserveAppointment($owner, $url, $uri, $name) {
    $principaluri = 'principals/users/' . $owner;

    // retrieving the calendar id
    $sql = 'SELECT id FROM oc_calendars ' .
            'WHERE `principaluri` = ? AND `uri` = ?';
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(1, $principaluri, \PDO::PARAM_STR);
    $stmt->bindParam(2, $url, \PDO::PARAM_STR);
    $executed = $stmt->execute();

    if($executed) {
      $row = $stmt->fetch();

       $stmt->closeCursor();
       $calendarid =  $row['id'];

       // retrieving the event id and calendar data
       $sql = 'SELECT id, calendardata FROM oc_calendarobjects ' .
               'WHERE `calendarid` = ? AND `uri` = ?';
       $stmt = $this->db->prepare($sql);
       $stmt->bindParam(1, $calendarid, \PDO::PARAM_STR);
       $stmt->bindParam(2, $uri, \PDO::PARAM_STR);
       $executed = $stmt->execute();

       if ($executed) {
         $row = $stmt->fetch();
         $stmt->closeCursor();

         $id = $row['id'];
         $calendardata = $row['calendardata'];

         //generate updated description of VCal/VEvent
         $description = "DESCRIPTION:";
         $summary = "SUMMARY:";
         $status = "STATUS";

         $summary_index = strpos($calendardata, $summary);
         $post_summary_index = strpos($calendardata, "\n", $summary_index);

         $altered_event = "";
         $appointment = "Appointment with "  . $name;

         if(!strpos($calendardata, $description)) {
             $data_pre_summary = substr($calendardata, 0, $summary_index);
             $data_post_summary = substr($calendardata, $post_summary_index);

             $altered_event = $data_pre_summary . $summary . $appointment . "\n" . $description . $appointment . $data_post_summary;
         } else {

             $description_index = strpos($calendardata, $description) + strlen($description);

             $data_pre_summary = substr($calendardata, 0, $summary_index);
             $data_post_description = substr($calendardata, $description_index);

             $altered_event = $data_pre_summary . $summary . $appointment . "\n" . $description . $appointment . ". " . $data_post_description;
         }

         $sql = 'UPDATE oc_calendarobjects ' .
                'SET `calendardata` = ? ' .
                 'WHERE `id` = ?';
         $stmt = $this->db->prepare($sql);
         $stmt->bindParam(1, $altered_event, \PDO::PARAM_STR);
         $stmt->bindParam(2, $id, \PDO::PARAM_STR);
         $executed = $stmt->execute();

         if ($executed) {
           return Http::STATUS_OK;
         }
       }
    }

    return Http::STATUS_BAD_REQUEST;
	}


	/**
	 * @param string $token
   * @param string $html
   * @PublicPage
	 * @NoCSRFRequired
	 *
	 * @return int
	 */
	public function generateTemplate($token, $html) {
    $this->config->setAppValue($this->appName, $token, $html);

    return Http::STATUS_OK;
  }

  /**
	 * @param string $token
   * @PublicPage
	 * @NoCSRFRequired
	 *
	 * @return TemplateResponse
	 */
	public function index($token) {

		$html = (string) $this->config->getAppValue($this->appName, $token);

    if($html == null) {
      return new TemplateResponse('calendar', 'main-page-expired');
    }

    $this->config->setAppValue($this->appName, $token, '');
    return new TemplateResponse('calendar', 'one-time-link', [
      'html' => $html,
    ]);
  }
}
