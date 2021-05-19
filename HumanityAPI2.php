<?php
namespace Ndcisiv;

/**
 * HumanityAPI2 is a PHP SDK for the Humanity v2.0 API
 *
 * @package HumanityAPI2
 * @author  Justin Patterson (justin@ndcisiv.com)
 * @version 1.0
 * @access  public
 * @link    https://platform.humanity.com/reference
 *
 * Permission Levels:
 * 2 - Manager
 * 3 - Supervisor
 * 4 - Scheduler
 * 5 - Employee
 * 6 - Accountant
 * 7 - Schedule Viewer
 */

class HumanityAPI2
{

    /** @var mixed $clientId contains the App ID of your API v2 application */
    private $clientId;

    /** @var mixed $clientSecret contains the App Secret of your API v2 application */
    private $clientSecret;

    /** @var mixed $username contains your username */
    private $username;

    /** @var mixed $password contains your password */
    private $password;

    /** @var mixed|null $redirectUri contains the Redirect URI of your API v2 application */
    private $redirectUri;

    /** @var mixed $accessToken contains the current Access Token  */
    private $accessToken;

    /** @var mixed $refreshToken contains the current Refresh Token to be used if the Access Token expires */
    private $refreshToken;

    /** @var string API_OAUTH is the OAuth2 URL to authenticate against */
    const API_OAUTH = 'https://www.humanity.com/oauth2/token.php';

    /** @var string API_ENDPOINT is the Humanity v2 API URL */
    const API_ENDPOINT = 'https://www.humanity.com/api/v2/';

    /** @var string[] REQUIRED_KEYS is an array of which configuration keys are required */
    const REQUIRED_KEYS = ['client_id','client_secret','grant_type','username','password'];


    /**
     * HumanityAPI2 constructor.
     * @param array $config
     */
    public function __construct(array $config=[])
    {
        try {
            if (count(array_intersect_key(array_flip(self::REQUIRED_KEYS), $config)) === count(self::REQUIRED_KEYS)) {
                if (!function_exists('curl_init')) {
                    throw new \Exception($this->internalErrors(2));
                }
                if (!function_exists('json_decode')) {
                    throw new \Exception($this->internalErrors(3));
                }

                $this->clientId = $config['client_id'];
                $this->clientSecret = $config['client_secret'];
                $this->username = $config['username'];
                $this->password = $config['password'];
                $this->redirectUri = array_key_exists('redirect_uri',$config) ? $config['redirect_uri'] : null;

                $this->authenticate();
            } else {
                throw new \Exception($this->internalErrors(1));
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Used to refresh the authentication token if it expires
     */
    public function refreshToken()
    {
        $this->refreshAuthentication();
    }


    /**
     ******************************************************************
     *************************** ME Functions *************************
     ******************************************************************
     */

    /**
     * Permission level: 7
     * @return bool|string
     */
    public function getMe()
    {
        $uri = self::API_ENDPOINT.'me';
        return $this->getResponse($uri);
    }


    /**
     ******************************************************************
     *********************** COMPANIES Functions **********************
     ******************************************************************
     */

    /**
     * Permission level: 3
     * @param array $queryParams
     * @return bool|string
     */
    public function getCompanies(array $queryParams=[])
    {
        $params = (!empty($queryParams)) ? '?'.http_build_query($queryParams).'&' : '';
        $uri = self::API_ENDPOINT.'companies'.$params;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 3
     * @param $id
     * @return bool|string
     */
    public function getCompany($id)
    {
        $uri = self::API_ENDPOINT.'companies/'.$id;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 7
     * @return bool|string
     */
    public function getCompanySettings()
    {
        $uri = self::API_ENDPOINT.'company/settings';
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 2
     * @param array $data
     * @return mixed
     */
    public function putCompanySettings(array $data=[])
    {
        $uri = self::API_ENDPOINT.'company/settings';
        return $this->putResponse($uri,$data);
    }

    /**
     * Permission level: 5
     * @return bool|string
     */
    public function getNumberOfRequests()
    {
        $uri = self::API_ENDPOINT.'company/number_of_requests';
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 5
     * @return bool|string
     */
    public function getBusiness()
    {
        $uri = self::API_ENDPOINT.'company/business';
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 5
     * @return bool|string
     */
    public function getGroupPermissions()
    {
        $uri = self::API_ENDPOINT.'company/group_perms';
        return $this->getResponse($uri);
    }


    /**
     ******************************************************************
     *********************** LOCATIONS Functions **********************
     ******************************************************************
     */

    /**
     * Permission level: 7
     * @param array $queryParams
     * @return bool|string
     */
    public function getLocations(array $queryParams=[])
    {
        $params = (!empty($queryParams)) ? '?'.http_build_query($queryParams).'&' : '';
        $uri = self::API_ENDPOINT.'locations'.$params;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 5
     * @param $id
     * @return bool|string
     */
    public function getLocation($id)
    {
        $uri = self::API_ENDPOINT.'locations/'.$id;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 5
     * @param array $data
     * @return mixed
     */
    public function postLocation(array $data=[])
    {
        $uri = self::API_ENDPOINT.'locations';
        return $this->postResponse($uri,$data);
    }

    /**
     * Permission level: 3
     * @param $id
     * @param array $data
     * @return mixed
     */
    public function putLocation($id,array $data=[])
    {
        $uri = self::API_ENDPOINT.'locations/'.$id;
        return $this->putResponse($uri,$data);
    }

    /**
     * Permission level: 5
     * @param $id
     * @return mixed
     */
    public function deleteLocation($id)
    {
        $uri = self::API_ENDPOINT.'locations/'.$id;
        return $this->deleteResponse($uri);
    }


    /**
     ******************************************************************
     *********************** POSITIONS Functions **********************
     ******************************************************************
     */

    /**
     * Permission level: 7
     * @param array $queryParams
     * @return bool|string
     */
    public function getPositions(array $queryParams=[])
    {
        $params = (!empty($queryParams)) ? '?'.http_build_query($queryParams).'&' : '';
        $uri = self::API_ENDPOINT.'positions'.$params;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 7
     * @param $id
     * @return bool|string
     */
    public function getPosition($id)
    {
        $uri = self::API_ENDPOINT.'positions/'.$id;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 3
     * @param array $data
     * @return mixed
     */
    public function postPosition(array $data=[])
    {
        $uri = self::API_ENDPOINT.'positions';
        return $this->postResponse($uri,$data);
    }

    /**
     * Permission level: 3
     * @param $id
     * @param array $data
     * @return mixed
     */
    public function putPosition($id,array $data=[])
    {
        $uri = self::API_ENDPOINT.'positions/'.$id;
        return $this->putResponse($uri,$data);
    }

    /**
     * Permission level: 3
     * @param $id
     * @return mixed
     */
    public function deletePosition($id)
    {
        $uri = self::API_ENDPOINT.'positions/'.$id;
        return $this->deleteResponse($uri);
    }

    /**
     * Permission level: 7
     * @param $scheduleId
     * @return bool|string
     */
    public function getPositionBreakRules($scheduleId)
    {
        $uri = self::API_ENDPOINT.'positions/'.$scheduleId.'/breakrules';
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 3
     * @param array $data
     * @return mixed
     */
    public function postPositionBreakRules(array $data=[])
    {
        $uri = self::API_ENDPOINT.'breakrules';
        return $this->postResponse($uri,$data);
    }

    /**
     * Permission level: 3
     * @param $id
     * @return mixed
     */
    public function deletePositionBreakRules($id)
    {
        $uri = self::API_ENDPOINT.'breakrules/'.$id;
        return $this->deleteResponse($uri);
    }


    /**
     ******************************************************************
     *********************** EMPLOYEES Functions **********************
     ******************************************************************
     */

    /**
     * Permission level: 7
     * @param array $queryParams
     * @return bool|string
     */
    public function getEmployees(array $queryParams=[])
    {
        $params = (!empty($queryParams)) ? '?'.http_build_query($queryParams).'&' : '';
        $uri = self::API_ENDPOINT.'employees'.$params;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 5
     * @param $id
     * @return bool|string
     */
    public function getEmployee($id)
    {
        $uri = self::API_ENDPOINT.'employees/'.$id;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 5
     * @param $employeeId
     * @return bool|string
     */
    public function getEmployeeByEmployeeId($employeeId)
    {
        $uri = self::API_ENDPOINT.'employees/eid/'.$employeeId;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 4
     * @param array $data
     * @return mixed
     */
    public function postEmployee(array $data=[])
    {
        $uri = self::API_ENDPOINT.'employees';
        return $this->postResponse($uri,$data);
    }

    /**
     * Permission level: 5
     * @param $id
     * @param array $data
     * @return mixed
     */
    public function putEmployee($id,array $data=[])
    {
        $uri = self::API_ENDPOINT.'employees/'.$id;
        return $this->putResponse($uri,$data);
    }

    /**
     * Permission level: 5
     * @param $id
     * @return mixed
     */
    public function deleteEmployee($id)
    {
        $uri = self::API_ENDPOINT.'employees/'.$id;
        return $this->deleteResponse($uri);
    }


    /**
     ******************************************************************
     ***************** EMPLOYEE POSITIONS Functions *******************
     ******************************************************************
     */

    /**
     * Permission level: 7
     * @param $employeeId
     * @return bool|string
     */
    public function getEmployeePositions($employeeId)
    {
        $uri = self::API_ENDPOINT.'employees/'.$employeeId.'/positions';
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 7
     * @param $employeeId
     * @param $positionId
     * @return bool|string
     */
    public function getEmployeePosition($employeeId,$positionId)
    {
        $uri = self::API_ENDPOINT.'employees/'.$employeeId.'/positions/'.$positionId;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 3
     * @param $employeeId
     * @param $positionId
     * @param array $data
     * @return mixed
     */
    public function postEmployeePosition($employeeId,$positionId,array $data=[])
    {
        $uri = self::API_ENDPOINT.'employees/'.$employeeId.'/positions/'.$positionId;
        return $this->postResponse($uri,$data);
    }

    /**
     * Permission level: 3
     * @param $employeeId
     * @param $positionId
     * @return mixed
     */
    public function deleteEmployeePosition($employeeId,$positionId)
    {
        $uri = self::API_ENDPOINT.'employees/'.$employeeId.'/positions/'.$positionId;
        return $this->deleteResponse($uri);
    }

    /**
     * Permission level: 3
     * @param $employeeId
     * @param $positionId
     * @param array $data
     * @return mixed
     */
    public function putEmployeePosition($employeeId,$positionId,array $data=[])
    {
        $uri = self::API_ENDPOINT.'employees/'.$employeeId.'/positions/'.$positionId;
        return $this->putResponse($uri,$data);
    }

    /**
     * Permission level: 5
     * @param $employeeId
     * @return bool|string
     */
    public function getEmployeeManagePositions($employeeId)
    {
        $uri = self::API_ENDPOINT.'employees/'.$employeeId.'/manage-positions';
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 5
     * @param $employeeId
     * @param array $data
     * @return mixed
     */
    public function postEmployeeManagePositions($employeeId,array $data=[])
    {
        $uri = self::API_ENDPOINT.'employees/'.$employeeId.'/manage-positions';
        return $this->postResponse($uri,$data);
    }


    /**
     ******************************************************************
     ************************* SHIFTS Functions ***********************
     ******************************************************************
     */

    /**
     * Permission level: 7
     * @param array $queryParams
     * @return bool|string
     */
    public function getShifts(array $queryParams=[])
    {
        $params = (!empty($queryParams)) ? '?'.http_build_query($queryParams).'&' : '';
        $uri = self::API_ENDPOINT.'shifts'.$params;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 7
     * @param $id
     * @param array $queryParams
     * @return bool|string
     */
    public function getShift($id,array $queryParams=[])
    {
        $params = (!empty($queryParams)) ? '?'.http_build_query($queryParams).'&' : '';
        $uri = self::API_ENDPOINT.'shifts/'.$id.$params;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 3
     * @param array $data
     * @return mixed
     */
    public function postShift(array $data=[])
    {
        $uri = self::API_ENDPOINT.'shifts';
        return $this->postResponse($uri,$data);
    }

    /**
     * Permission level: 5
     * @param $id
     * @param array $data
     * @return mixed
     */
    public function putShift($id,array $data=[])
    {
        $uri = self::API_ENDPOINT.'shifts/'.$id;
        return $this->putResponse($uri,$data);
    }

    /**
     * Permission level: 3
     * @param $id
     * @param array $data
     * @return mixed
     */
    public function deleteShift($id,array $data=[])
    {
        $uri = self::API_ENDPOINT.'shifts/'.$id;
        return $this->deleteResponse($uri,$data);
    }

    /**
     * Permission level: 5
     * @param array $queryParams
     * @return bool|string
     */
    public function getClear(array $queryParams=[])
    {
        $params = (!empty($queryParams)) ? '?'.http_build_query($queryParams).'&' : '';
        $uri = self::API_ENDPOINT.'shifts/clear'.$params;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 5
     * @param array $queryParams
     * @return bool|string
     */
    public function getPublish(array $queryParams=[])
    {
        $params = (!empty($queryParams)) ? '?'.http_build_query($queryParams).'&' : '';
        $uri = self::API_ENDPOINT.'shifts/publish'.$params;
        return $this->getResponse($uri);
    }


    /**
     ******************************************************************
     *********************** SHIFT SWAP Functions *********************
     ******************************************************************
     */

    /**
     * Permission level: 5
     * @param $shiftId
     * @param array $data
     * @return mixed
     */
    public function postSwap($shiftId,array $data=[])
    {
        $uri = self::API_ENDPOINT.'shifts/'.$shiftId.'/swap';
        return $this->postResponse($uri,$data);
    }

    /**
     * Permission level: 5
     * @param $tradeId
     * @param array $data
     * @return mixed
     */
    public function putSwap($tradeId,array $data=[])
    {
        $uri = self::API_ENDPOINT.'swap/'.$tradeId;
        return $this->putResponse($uri,$data);
    }


    /**
     ******************************************************************
     ************************* TRADES Functions ***********************
     ******************************************************************
     */

    /**
     * Permission level: 5
     * @param $tradeMode
     * @return bool|string
     */
    public function getTrades($tradeMode)
    {
        $uri = self::API_ENDPOINT.'trade/'.$tradeMode;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 5
     * @param $id
     * @param array $queryParams
     * @return bool|string
     */
    public function getTrade($id,array $queryParams=[])
    {
        $params = (!empty($queryParams)) ? '?'.http_build_query($queryParams).'&' : '';
        $uri = self::API_ENDPOINT.'trade/'.$id.$params;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 5
     * @param array $data
     * @return mixed
     */
    public function postTrade(array $data=[])
    {
        $uri = self::API_ENDPOINT.'trades';
        return $this->postResponse($uri,$data);
    }

    /**
     * Permission level: 5
     * @param $tradeId
     * @param array $data
     * @return mixed
     */
    public function putTrade($tradeId,array $data=[])
    {
        $uri = self::API_ENDPOINT.'trades/'.$tradeId;
        return $this->putResponse($uri,$data);
    }


    /**
     ******************************************************************
     ********************* SHIFTS APPROVE Functions *******************
     ******************************************************************
     */

    /**
     * Permission level: 3
     * @param $id
     * @return bool|string
     */
    public function getShiftApprove($id)
    {
        $uri = self::API_ENDPOINT.'shifts/'.$id.'/approve';
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 3
     * @param $id
     * @param array $data
     * @return mixed
     */
    public function postShiftApprove($id,array $data=[])
    {
        $uri = self::API_ENDPOINT.'shifts/'.$id.'/approve';
        return $this->postResponse($uri,$data);
    }

    /**
     * Permission level: 3
     * @param $id
     * @param array $data
     * @return mixed
     */
    public function putShiftApprove($id,array $data=[])
    {
        $uri = self::API_ENDPOINT.'shifts/'.$id.'/approve';
        return $this->putResponse($uri,$data);
    }

    /**
     * Permission level: 3
     * @param $id
     * @return mixed
     */
    public function deleteShiftApprove($id)
    {
        $uri = self::API_ENDPOINT.'shifts/'.$id.'/approve';
        return $this->deleteResponse($uri);
    }


    /**
     ******************************************************************
     *********************** SHIFT BREAK Functions ********************
     ******************************************************************
     */

    /**
     * Permission level: 5
     * @param $shiftId
     * @param array $queryParams
     * @return bool|string
     */
    public function getShiftBreaks($shiftId,array $queryParams=[])
    {
        $params = (!empty($queryParams)) ? '?'.http_build_query($queryParams).'&' : '';
        $uri = self::API_ENDPOINT.'shifts/'.$shiftId.'/shiftbreaks'.$params;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 4
     * @param $shiftId
     * @param array $data
     * @return mixed
     */
    public function postShiftBreaks($shiftId,array $data=[])
    {
        $uri = self::API_ENDPOINT.'shifts/'.$shiftId.'/shiftbreaks';
        return $this->postResponse($uri,$data);
    }

    /**
     * Permission level: 4
     * @param $shiftId
     * @param array $data
     * @return mixed
     */
    public function putShiftBreaks($shiftId,array $data=[])
    {
        $uri = self::API_ENDPOINT.'shifts/'.$shiftId.'/shiftbreaks';
        return $this->putResponse($uri,$data);
    }

    /**
     * Permission level: 4
     * @param $shiftId
     * @param array $data
     * @return mixed
     */
    public function deleteShiftBreaks($shiftId,array $data=[])
    {
        $uri = self::API_ENDPOINT.'shifts/'.$shiftId.'/shiftbreaks';
        return $this->deleteResponse($uri,$data);
    }


    /**
     ******************************************************************
     *********************** TIMECLOCKS Functions ********************
     ******************************************************************
     */

    /**
     * Permission level: 5
     * @param array $queryParams
     * @return bool|string
     */
    public function getTimeclocks(array $queryParams=[])
    {
        $params = (!empty($queryParams)) ? '?'.http_build_query($queryParams).'&' : '';
        $uri = self::API_ENDPOINT.'timeclocks'.$params;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 5
     * @param $id
     * @return bool|string
     */
    public function getTimeclock($id)
    {
        $uri = self::API_ENDPOINT.'timeclocks/'.$id;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 5
     * @param $employeeId
     * @param int $details
     * @return bool|string
     */
    public function getTimeclockStatus($employeeId,$details=0)
    {
        $uri = self::API_ENDPOINT.'timeclocks/status/'.$employeeId.'/'.$details;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 5
     * @param array $data
     * @return mixed
     */
    public function postTimeclock(array $data=[])
    {
        $uri = self::API_ENDPOINT.'timeclocks';
        return $this->postResponse($uri,$data);
    }

    /**
     * Permission level: 5
     * @param $id
     * @param array $data
     * @return mixed
     */
    public function putTimeclock($id,array $data=[])
    {
        $uri = self::API_ENDPOINT.'timeclocks/'.$id;
        return $this->putResponse($uri,$data);
    }

    /**
     * Permission level: 5
     * @param $id
     * @return mixed
     */
    public function deleteTimeclock($id)
    {
        $uri = self::API_ENDPOINT.'timeclocks/'.$id;
        return $this->deleteResponse($uri);
    }

    /**
     * Permission level: 5
     * @param array $data
     * @return mixed
     */
    public function postAddClockTime(array $data=[])
    {
        $uri = self::API_ENDPOINT.'timeclock/addclocktime';
        return $this->postResponse($uri,$data);
    }

    /**
     * Permission level: 5
     * @param array $data
     * @return mixed
     */
    public function postSaveNote(array $data=[])
    {
        $uri = self::API_ENDPOINT.'timeclock/savenote';
        return $this->postResponse($uri,$data);
    }

    /**
     * Permission level: 5
     * @param array $data
     * @return mixed
     */
    public function putManageTimeClock(array $data=[])
    {
        $uri = self::API_ENDPOINT.'timeclock/manage';
        return $this->putResponse($uri,$data);
    }

    /**
     * Permission level: 5
     * @param array $data
     * @return mixed
     */
    public function putForceClockOut(array $data=[])
    {
        $uri = self::API_ENDPOINT.'timeclock/forceclockout';
        return $this->putResponse($uri,$data);
    }


    /**
     ******************************************************************
     ******************** TIMECLOCK EVENTS Functions ******************
     ******************************************************************
     */

    /**
     * Permission level: 5
     * @param $timeclockId
     * @return bool|string
     */
    public function getTimeclockEvent($timeclockId)
    {
        $uri = self::API_ENDPOINT.'timeclocks/event/'.$timeclockId;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 5
     * @param $timeclockId
     * @param array $data
     * @return mixed
     */
    public function postTimeclockEvent($timeclockId,array $data=[])
    {
        $uri = self::API_ENDPOINT.'timeclocks/event/'.$timeclockId;
        return $this->postResponse($uri,$data);
    }

    /**
     * Permission level: 5
     * @param $timeclockId
     * @param array $data
     * @return mixed
     */
    public function putTimeclockEvent($timeclockId,array $data=[])
    {
        $uri = self::API_ENDPOINT.'timeclocks/event/'.$timeclockId;
        return $this->putResponse($uri,$data);
    }

    /**
     * BROKEN : The endpoint is currently broken, awaiting Humanity to fix it
     * Permission level: 5
     * @param $timeclockId
     * @param array $data
     * @return mixed
     */
    public function deleteTimeclockEvent($timeclockId,array $data=[])
    {
        $uri = self::API_ENDPOINT.'timeclocks/event/'.$timeclockId;
        return $this->deleteResponse($uri,$data);
    }


    /**
     ******************************************************************
     ******************* TIMECLOCK LOCATION Functions *****************
     ******************************************************************
     */

    /**
     * Permission level: 7
     * @return bool|string
     */
    public function getTimeclockLocations()
    {
        $uri = self::API_ENDPOINT.'timeclocks/locations';
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 3
     * @param array $data
     * @return mixed
     */
    public function postTimeclockLocation(array $data=[])
    {
        $uri = self::API_ENDPOINT.'timeclocks/locations';
        return $this->postResponse($uri,$data);
    }

    /**
     * Permission level: 3
     * @param $timeclockId
     * @param array $data
     * @return mixed
     */
    public function deleteTimeclockLocation($timeclockId,array $data=[])
    {
        $uri = self::API_ENDPOINT.'timeclocks/locations/'.$timeclockId;
        return $this->deleteResponse($uri,$data);
    }


    /**
     ******************************************************************
     ******************* TIMECLOCK TERMINAL Functions *****************
     ******************************************************************
     */

    /**
     * Permission level: 7
     * @return bool|string
     */
    public function getTimeclockTerminals()
    {
        $uri = self::API_ENDPOINT.'timeclocks/terminal';
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 7
     * @param $id
     * @return bool|string
     */
    public function getTimeclockTerminal($id)
    {
        $uri = self::API_ENDPOINT.'timeclocks/terminal/'.$id;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 3
     * @param array $data
     * @return mixed
     */
    public function postTimeclockTerminal(array $data=[])
    {
        $uri = self::API_ENDPOINT.'timeclocks/terminal';
        return $this->postResponse($uri,$data);
    }

    /**
     * Permission level: 3
     * @param $terminalId
     * @param array $data
     * @return mixed
     */
    public function putTimeclockTerminal($terminalId,array $data=[])
    {
        $uri = self::API_ENDPOINT.'timeclocks/terminal/'.$terminalId;
        return $this->putResponse($uri,$data);
    }

    /**
     * Permission level: 3
     * @param $terminalId
     * @return mixed
     */
    public function deleteTimeclockTerminal($terminalId)
    {
        $uri = self::API_ENDPOINT.'timeclocks/terminal/'.$terminalId;
        return $this->deleteResponse($uri);
    }


    /**
     ******************************************************************
     ************************ TERMINAL Functions **********************
     ******************************************************************
     */

    /**
     * Permission level: 5
     * @param $terminalKey
     * @param array $data
     * @return mixed
     */
    public function postTerminalClockin($terminalKey,array $data=[])
    {
        $uri = self::API_ENDPOINT.'terminal/clockin/'.$terminalKey;
        return $this->postResponse($uri,$data);
    }

    /**
     * Permission level: 5
     * @param $terminalKey
     * @param array $data
     * @return mixed
     */
    public function postTerminalLogin($terminalKey,array $data=[])
    {
        $uri = self::API_ENDPOINT.'terminal/login/'.$terminalKey;
        return $this->postResponse($uri,$data);
    }

    /**
     * Permission level: 5
     * @param $terminalKey
     * @param array $data
     * @return mixed
     */
    public function postTerminalClockout($terminalKey,array $data=[])
    {
        $uri = self::API_ENDPOINT.'terminal/clockout/'.$terminalKey;
        return $this->postResponse($uri,$data);
    }


    /**
     ******************************************************************
     ************************ FORECAST Functions **********************
     ******************************************************************
     */

    /**
     * Permission level: 5
     * @return bool|string
     */
    public function getForecastDatatypes()
    {
        $uri = self::API_ENDPOINT.'forecast/datatypes';
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 5
     * @param $datatypeId
     * @return bool|string
     */
    public function getForecastDatatypeById($datatypeId)
    {
        $uri = self::API_ENDPOINT.'forecast/datatypes/'.$datatypeId;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 5
     * @param $uniqueId
     * @return bool|string
     */
    public function getForecastDatatypeByUniqueId($uniqueId)
    {
        $uri = self::API_ENDPOINT.'forecast/datatypes/unique_id/'.$uniqueId;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 5
     * @param array $data
     * @return mixed
     */
    public function postForecastDatatype(array $data=[])
    {
        $uri = self::API_ENDPOINT.'forecast/datatypes';
        return $this->postResponse($uri,$data);
    }

    /**
     * Permission level: 5
     * @param $datatypeId
     * @param array $data
     * @return mixed
     */
    public function putForecastDatatypeById($datatypeId,array $data=[])
    {
        $uri = self::API_ENDPOINT.'forecast/datatypes/'.$datatypeId;
        return $this->putResponse($uri,$data);
    }

    /**
     * Permission level: 5
     * @param $uniqueIdUid
     * @param array $data
     * @return mixed
     */
    public function putForecastDatatypeByUniqueId($uniqueIdUid,array $data=[])
    {
        $uri = self::API_ENDPOINT.'forecast/datatype/unique_id/'.$uniqueIdUid;
        return $this->putResponse($uri,$data);
    }

    /**
     * Permission level: 5
     * @param $datatypeId
     * @return mixed
     */
    public function deleteForecastDatatype($datatypeId)
    {
        $uri = self::API_ENDPOINT.'forecast/datatypes/'.$datatypeId;
        return $this->deleteResponse($uri);
    }

    /**
     * Permission level: 5
     * @param $uniqueId
     * @return mixed
     */
    public function deleteForecastDatatypeByUniqueId($uniqueId)
    {
        $uri = self::API_ENDPOINT.'forecast/datatypes/unique_id/'.$uniqueId;
        return $this->deleteResponse($uri);
    }

    /**
     * Permission level: 5
     * @param $datapointId
     * @return bool|string
     */
    public function getDatapoint($datapointId)
    {
        $uri = self::API_ENDPOINT.'forecast/datapoint/'.$datapointId;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 5
     * @param array $data
     * @return mixed
     */
    public function postDatapoint(array $data=[])
    {
        $uri = self::API_ENDPOINT.'forecast/datapoint';
        return $this->postResponse($uri,$data);
    }

    /**
     * Permission level: 5
     * @param $datapointId
     * @param array $data
     * @return mixed
     */
    public function putDatapoint($datapointId,array $data=[])
    {
        $uri = self::API_ENDPOINT.'forecast/datapoint/'.$datapointId;
        return $this->putResponse($uri,$data);
    }

    /**
     * Permission level: 5
     * @param $datapointId
     * @return mixed
     */
    public function deleteDatapoint($datapointId)
    {
        $uri = self::API_ENDPOINT.'forecast/datapoint/'.$datapointId;
        return $this->deleteResponse($uri);
    }

    /**
     * Permission level: 5
     * @param $datapointId
     * @param $firstDay
     * @param $lastDay
     * @return bool|string
     */
    public function getDatapoints($datapointId,$firstDay,$lastDay)
    {
        $uri = self::API_ENDPOINT.'forecast/datapoints/'.$datapointId.'?first_day='.$firstDay.'&last_day='.$lastDay.'&';
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 5
     * @param $datatypeId
     * @param array $data
     * @return mixed
     */
    public function postCopyDatapoints($datatypeId,array $data=[])
    {
        $uri = self::API_ENDPOINT.'forecast/copy/'.$datatypeId;
        return $this->postResponse($uri,$data);
    }

    /**
     * Permission level: 5
     * @param $uniqueId
     * @param $firstDay
     * @param $lastDay
     * @return bool|string
     */
    public function getDatapointByDatatypeUniqueId($uniqueId,$firstDay,$lastDay)
    {
        $uri = self::API_ENDPOINT.'forecast/datapoints/unique_id/'.$uniqueId.'?first_day='.$firstDay.'&last_day='.$lastDay.'&';
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 5
     * @param $uniqueIdUid
     * @param array $data
     * @return mixed
     */
    public function putDatapointByDatatypeUniqueId($uniqueIdUid,array $data=[])
    {
        $uri = self::API_ENDPOINT.'forecast/datapoint/unique_id/'.$uniqueIdUid;
        return $this->putResponse($uri,$data);
    }

    /**
     * Permission level: 5
     * @param $uniqueIdUid
     * @param array $data
     * @return mixed
     */
    public function postCopyDatapointsByDatatypeUniqueId($uniqueIdUid,array $data=[])
    {
        $uri = self::API_ENDPOINT.'forecast/copy/unique_id/'.$uniqueIdUid;
        return $this->postResponse($uri,$data);
    }

    /**
     * Permission level: 5
     * @param array $data
     * @return mixed
     */
    public function createDatapoints(array $data=[])
    {
        $uri = self::API_ENDPOINT.'forecast/datapoint';
        return $this->postResponseJson($uri,$data);
    }


    /**
     ******************************************************************
     ************************* LEAVES Functions ***********************
     ******************************************************************
     */

    /**
     * Permission level: 7
     * @param array $queryParams
     * @return bool|string
     */
    public function getLeaves(array $queryParams=[])
    {
        $params = (!empty($queryParams)) ? '?'.http_build_query($queryParams).'&' : '';
        $uri = self::API_ENDPOINT.'leaves'.$params;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 7
     * @param $id
     * @param array $queryParams
     * @return bool|string
     */
    public function getLeave($id,array $queryParams=[])
    {
        $params = (!empty($queryParams)) ? '?'.http_build_query($queryParams).'&' : '';
        $uri = self::API_ENDPOINT.'leaves/'.$id.$params;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 5
     * @param array $data
     * @return mixed
     */
    public function postLeaveRequest(array $data=[])
    {
        $data['is_hourly'] = false;
        $uri = self::API_ENDPOINT.'leaves';
        return $this->postResponse($uri,$data);
    }

    /**
     * Permission level: 5
     * @param array $data
     * @return mixed
     */
    public function postLeaveRequestHourly(array $data=[])
    {
        $data['is_hourly'] = true;
        $uri = self::API_ENDPOINT.'leaves';
        return $this->postResponse($uri,$data);
    }

    /**
     * Permission level: 5
     * @param $id
     * @param array $data
     * @return mixed
     */
    public function putLeave($id,array $data=[])
    {
        $uri = self::API_ENDPOINT.'leaves/'.$id;
        return $this->putResponse($uri,$data);
    }

    /**
     * Permission level: 7
     * @param $leaveId
     * @return mixed
     */
    public function putApprovingLeaveRequest($leaveId)
    {
        $data['status'] = 1;
        $uri = self::API_ENDPOINT.'leaves/'.$leaveId;
        return $this->putResponse($uri,$data);
    }

    /**
     * Permission level: 7
     * @param $leaveId
     * @return mixed
     */
    public function putRejectingLeaveRequest($leaveId)
    {
        $data['status'] = -1;
        $uri = self::API_ENDPOINT.'leaves/'.$leaveId;
        return $this->putResponse($uri,$data);
    }

    /**
     * Permission level: 5
     * @param $id
     * @return mixed
     */
    public function deleteLeave($id)
    {
        $uri = self::API_ENDPOINT.'leaves/'.$id;
        return $this->deleteResponse($uri);
    }


    /**
     ******************************************************************
     ********************* LEAVE-TYPES Functions **********************
     ******************************************************************
     */

    /**
     * Permission level: 7
     * @return bool|string
     */
    public function getLeaveTypes()
    {
        $uri = self::API_ENDPOINT.'leave-types';
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 5
     * @param $leavetypeId
     * @return mixed
     */
    public function deleteLeaveType($leavetypeId)
    {
        $uri = self::API_ENDPOINT.'leave-types/'.$leavetypeId;
        return $this->deleteResponse($uri);
    }

    /**
     * Permission level: 5
     * @param array $data
     * @return mixed
     */
    public function postLeaveType(array $data=[])
    {
        $uri = self::API_ENDPOINT.'leave-types';
        return $this->postResponse($uri,$data);
    }

    /**
     * Permission level: 7
     * @param $id
     * @param array $data
     * @return mixed
     */
    public function putLeaveType($id,array $data=[])
    {
        $uri = self::API_ENDPOINT.'leave-types/'.$id;
        return $this->putResponse($uri,$data);
    }

    /**
     * Permission level: 7
     * @param $employeeId
     * @return bool|string
     */
    public function getLeaveTypesForASpecificEmployee($employeeId)
    {
        $uri = self::API_ENDPOINT.'employees/'.$employeeId.'/leave-types';
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 7
     * @param $employeeId
     * @param array $data
     * @return mixed
     */
    public function putEnablingDisablingLeaveTypesForEmployees($employeeId,array $data=[])
    {
        $uri = self::API_ENDPOINT.'employees/'.$employeeId.'/leave-types';
        return $this->putResponse($uri,$data);
    }


    /**
     ******************************************************************
     ****************** PAYROLL RATECARDS Functions *******************
     ******************************************************************
     */

    /**
     * Permission level: 5
     * @return bool|string
     */
    public function getRatecards()
    {
        $uri = self::API_ENDPOINT.'ratecards';
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 5
     * @param $id
     * @return bool|string
     */
    public function getRatecard($id)
    {
        $uri = self::API_ENDPOINT.'ratecards/'.$id;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 3
     * @param array $data
     * @return mixed
     */
    public function postRatecard(array $data=[])
    {
        $uri = self::API_ENDPOINT.'payroll/ratecards';
        return $this->postResponse($uri,$data);
    }

    /**
     * Permission level: 3
     * @param $id
     * @param array $data
     * @return mixed
     */
    public function putRatecard($id,array $data=[])
    {
        $uri = self::API_ENDPOINT.'payroll/ratecards/'.$id;
        return $this->putResponse($uri,$data);
    }

    /**
     * Permission level: 2
     * @param $id
     * @return mixed
     */
    public function deleteRatecard($id)
    {
        $uri = self::API_ENDPOINT.'payroll/ratecards/'.$id;
        return $this->deleteResponse($uri);
    }


    /**
     ******************************************************************
     ************************* BILLING Functions **********************
     ******************************************************************
     */

    /**
     * Permission level: 4
     * @param $startDate
     * @param $endDate
     * @return bool|string
     */
    public function getBudget($startDate,$endDate)
    {
        $uri = self::API_ENDPOINT.'sales/budget?start_date='.$startDate.'&end_date='.$endDate.'&';
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 4
     * @param array $data
     * @return mixed
     */
    public function postBudget(array $data=[])
    {
        $uri = self::API_ENDPOINT.'sales/budget';
        return $this->postResponse($uri,$data);
    }


    /**
     ******************************************************************
     ************************ MESSAGES Functions **********************
     ******************************************************************
     */

    /**
     * Permission level: 5
     * @param array $queryParams
     * @return bool|string
     */
    public function getMessages(array $queryParams=[])
    {
        $params = (!empty($queryParams)) ? '?'.http_build_query($queryParams).'&' : '';
        $uri = self::API_ENDPOINT.'messages'.$params;
        return $this->getResponse($uri);
    }

    /**
     * @param $id
     * @return bool|string
     */
    public function getMessage($id)
    {
        $uri = self::API_ENDPOINT.'messages/'.$id;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 5
     * @param array $data
     * @return mixed
     */
    public function postMessage(array $data=[])
    {
        $uri = self::API_ENDPOINT.'messages';
        return $this->postResponse($uri,$data);
    }

    /**
     * Permission level: 5
     * @param $conversationId
     * @param array $data
     * @return mixed
     */
    public function deleteMessage($conversationId,array $data=[])
    {
        $uri = self::API_ENDPOINT.'messages/'.$conversationId;
        return $this->deleteResponse($uri,$data);
    }


    /**
     ******************************************************************
     ************************** WALL Functions ************************
     ******************************************************************
     */

    /**
     * Permission level: 5
     * @return bool|string
     */
    public function getWallMssages()
    {
        $uri = self::API_ENDPOINT.'messages/wall';
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 5
     * @param array $data
     * @return mixed
     */
    public function postWallMessage(array $data=[])
    {
        $uri = self::API_ENDPOINT.'messages/wall';
        return $this->postResponse($uri,$data);
    }

    /**
     * Permission level: 5
     * @param $id
     * @param array $data
     * @return mixed
     */
    public function deleteWallMessage($id,array $data=[])
    {
        $uri = self::API_ENDPOINT.'messages/wall/'.$id;
        return $this->deleteResponse($uri,$data);
    }

    /**
     * Permission level: 5
     * @param $id
     * @param array $data
     * @return mixed
     */
    public function putWallMessage($id,array $data=[])
    {
        $uri = self::API_ENDPOINT.'messages/wall/'.$id;
        return $this->putResponse($uri,$data);
    }


    /**
     ******************************************************************
     ************************* NOTICES Functions **********************
     ******************************************************************
     */

    /**
     * Permission level: 5
     * @param array $queryParams
     * @return bool|string
     */
    public function getNotices(array $queryParams=[])
    {
        $params = (!empty($queryParams)) ? '?'.http_build_query($queryParams).'&' : '';
        $uri = self::API_ENDPOINT.'messages/notices'.$params;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 5
     * @param $id
     * @return bool|string
     */
    public function getNotice($id)
    {
        $uri = self::API_ENDPOINT.'messages/notices/'.$id;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 2
     * @param array $data
     * @return mixed
     */
    public function postNotice(array $data=[])
    {
        $uri = self::API_ENDPOINT.'messages/notices';
        return $this->postResponse($uri,$data);
    }

    /**
     * Permission level: 2
     * @param $id
     * @param array $data
     * @return mixed
     */
    public function putNotice($id,array $data=[])
    {
        $uri = self::API_ENDPOINT.'messages/notices/'.$id;
        return $this->putResponse($uri,$data);
    }

    /**
     * Permission level: 3
     * @param $id
     * @return mixed
     */
    public function deleteNotice($id)
    {
        $uri = self::API_ENDPOINT.'messages/notices/'.$id;
        return $this->deleteResponse($uri);
    }


    /**
     ******************************************************************
     ***************** EMPLOYEE AVAILABILITY Functions ****************
     ******************************************************************
     */

    /**
     * Permission level: 5
     * @param $employeeId
     * @param array $queryParams
     * @return bool|string
     */
    public function getWeeklyAvailability($employeeId,array $queryParams=[])
    {
        $params = (!empty($queryParams)) ? '?'.http_build_query($queryParams).'&' : '';
        $uri = self::API_ENDPOINT.'employees/'.$employeeId.'/availabilities/weekly'.$params;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 5
     * @param $employeeId
     * @param array $data
     * @return mixed
     */
    public function putWeeklyAvailability($employeeId,array $data=[])
    {
        $uri = self::API_ENDPOINT.'employees/'.$employeeId.'/availabilities/weekly';
        return $this->putResponse($uri,$data);
    }

    /**
     * Permission level: 5
     * @param $userId
     * @param array $data
     * @return mixed
     */
    public function deleteWeeklyAvailability($userId,array $data=[])
    {
        $uri = self::API_ENDPOINT.'employees/'.$userId.'/availabilities/weekly';
        return $this->deleteResponse($uri,$data);
    }

    /**
     * Permission level: 5
     * @param $employeeId
     * @param array $queryParams
     * @return bool|string
     */
    public function getFutureAvailability($employeeId,array $queryParams=[])
    {
        $params = (!empty($queryParams)) ? '?'.http_build_query($queryParams).'&' : '';
        $uri = self::API_ENDPOINT.'employees/'.$employeeId.'/availabilities/future'.$params;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 5
     * @param $employeeId
     * @param array $data
     * @return mixed
     */
    public function postFutureAvailability($employeeId,array $data=[])
    {
        $uri = self::API_ENDPOINT.'employees/'.$employeeId.'/availabilities/future';
        return $this->postResponse($uri,$data);
    }

    /**
     * Permission level: 5
     * @param $employeeId
     * @param array $data
     * @return mixed
     */
    public function putFutureAvailability($employeeId,array $data=[])
    {
        $uri = self::API_ENDPOINT.'availabilities/future/'.$employeeId;
        return $this->putResponse($uri,$data);
    }

    /**
     * Permission level: 5
     * @param $id
     * @return mixed
     */
    public function deleteFutureAvailability($id)
    {
        $uri = self::API_ENDPOINT.'/availabilities/future/'.$id;
        return $this->deleteResponse($uri);
    }

    /**
     * Permission level: 4
     * @param $employeeId
     * @param array $queryParams
     * @return bool|string
     */
    public function getAvailabilityApprove($employeeId,array $queryParams=[])
    {
        $params = (!empty($queryParams)) ? '?'.http_build_query($queryParams).'&' : '';
        $uri = self::API_ENDPOINT.'employees/'.$employeeId.'/availabilities/approve'.$params;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 4
     * @param $employeeId
     * @param array $data
     * @return mixed
     */
    public function postAvailabilityApprove($employeeId,array $data=[])
    {
        $uri = self::API_ENDPOINT.'employees/'.$employeeId.'/availabilities/approve';
        return $this->postResponse($uri,$data);
    }

    /**
     * Permission level: 4
     * @param $employeeId
     * @param array $data
     * @return mixed
     */
    public function putAvailabilityApprove($employeeId,array $data=[])
    {
        $uri = self::API_ENDPOINT.'employees/'.$employeeId.'/availabilities/approve';
        return $this->putResponse($uri,$data);
    }


    /**
     ******************************************************************
     *********************** TRAININGS Functions **********************
     ******************************************************************
     */

    /**
     * Permission level: 5
     * @return bool|string
     */
    public function getTrainingProgress()
    {
        $uri = self::API_ENDPOINT.'trainings/progress';
        return $this->getResponse($uri);
    }


    /**
     ******************************************************************
     ******************* TRAINING SECTIONS Functions ******************
     ******************************************************************
     */

    /**
     * Permission level: 5
     * @return bool|string
     */
    public function getTrainingSections()
    {
        $uri = self::API_ENDPOINT.'trainings/sections';
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 5
     * @param $id
     * @return bool|string
     */
    public function getTrainingSection($id)
    {
        $uri = self::API_ENDPOINT.'trainings/sections/'.$id;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 3
     * @param array $data
     * @return mixed
     */
    public function postTrainingSection(array $data=[])
    {
        $uri = self::API_ENDPOINT.'trainings/sections';
        return $this->postResponse($uri,$data);
    }

    /**
     * Permission level: 3
     * @param $id
     * @param array $data
     * @return mixed
     */
    public function putTrainingSection($id,array $data=[])
    {
        $uri = self::API_ENDPOINT.'trainings/sections/'.$id;
        return $this->putResponse($uri,$data);
    }

    /**
     * Permission level: 3
     * @param $id
     * @return mixed
     */
    public function deleteTrainingSection($id)
    {
        $uri = self::API_ENDPOINT.'/trainings/sections/'.$id;
        return $this->deleteResponse($uri);
    }

    /**
     * Permission level: 3
     * @param $id
     * @return mixed
     */
    public function putTrainingSectionSync($id)
    {
        $uri = self::API_ENDPOINT.'trainings/sections/'.$id.'/sync';
        return $this->putResponse($uri);
    }


    /**
     ******************************************************************
     ******************** TRAINING MODULES Functions ******************
     ******************************************************************
     */

    /**
     * Permission level: 5
     * @return bool|string
     */
    public function getTrainingModules()
    {
        $uri = self::API_ENDPOINT.'trainings/modules';
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 5
     * @param $id
     * @param array $queryParams
     * @return bool|string
     */
    public function getTrainingModule($id,array $queryParams=[])
    {
        $params = (!empty($queryParams)) ? '?'.http_build_query($queryParams).'&' : '';
        $uri = self::API_ENDPOINT.'trainings/modules/'.$id.$params;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 3
     * @param array $data
     * @return mixed
     */
    public function postTrainingModules(array $data=[])
    {
        $uri = self::API_ENDPOINT.'trainings/modules';
        return $this->postResponse($uri,$data);
    }

    /**
     * Permission level: 3
     * @param array $data
     * @return mixed
     */
    public function putTrainingModule(array $data=[])
    {
        $uri = self::API_ENDPOINT.'trainings/modules';
        return $this->putResponse($uri,$data);
    }

    /**
     * Permission level: 3
     * @param $id
     * @return mixed
     */
    public function deleteTrainingModule($id)
    {
        $uri = self::API_ENDPOINT.'/trainings/modules/'.$id;
        return $this->deleteResponse($uri);
    }

    /**
     * Permission level: 5
     * @param $moduleId
     * @param array $data
     * @return mixed
     */
    public function putTrainingModuleComplete($moduleId,array $data=[])
    {
        $uri = self::API_ENDPOINT.'trainings/modules/'.$moduleId.'/complete';
        return $this->putResponse($uri,$data);
    }

    /**
     * Permission level: 5
     * @param $moduleId
     * @return bool|string
     */
    public function getTrainingModuleSignature($moduleId)
    {
        $uri = self::API_ENDPOINT.'trainings/modules/'.$moduleId.'/digital_signature';
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 5
     * @param $moduleId
     * @param array $queryParams
     * @return bool|string
     */
    public function getTrainingModuleComments($moduleId,array $queryParams=[])
    {
        $params = (!empty($queryParams)) ? '?'.http_build_query($queryParams).'&' : '';
        $uri = self::API_ENDPOINT.'trainings/modules/'.$moduleId.'/comments'.$params;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 5
     * @param $moduleId
     * @param array $data
     * @return mixed
     */
    public function putTrainingModuleComments($moduleId,array $data=[])
    {
        $uri = self::API_ENDPOINT.'trainings/modules/'.$moduleId.'/comments';
        return $this->putResponse($uri,$data);
    }


    /**
     ******************************************************************
     ************************* REPORTS Functions **********************
     ******************************************************************
     */

    /**
     * Permission level: 5
     * @param array $queryParams
     * @return bool|string
     */
    public function getScheduleReport(array $queryParams=[])
    {
        $params = (!empty($queryParams)) ? '?'.http_build_query($queryParams).'&' : '';
        $uri = self::API_ENDPOINT.'reports/schedule'.$params;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 5
     * @param array $queryParams
     * @return bool|string
     */
    public function getTimesheetReport(array $queryParams=[])
    {
        $params = (!empty($queryParams)) ? '?'.http_build_query($queryParams).'&' : '';
        $uri = self::API_ENDPOINT.'reports/timesheets'.$params;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 5
     * @param array $queryParams
     * @return bool|string
     */
    public function getEmployeeReport(array $queryParams=[])
    {
        $params = (!empty($queryParams)) ? '?'.http_build_query($queryParams).'&' : '';
        $uri = self::API_ENDPOINT.'reports/employee'.$params;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 3
     * @param array $queryParams
     * @return bool|string
     */
    public function getCustomReport(array $queryParams=[])
    {
        $params = (!empty($queryParams)) ? '?'.http_build_query($queryParams).'&' : '';
        $uri = self::API_ENDPOINT.'reports/custom'.$params;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 3
     * @param array $queryParams
     * @return bool|string
     */
    public function getDailyPeakHoursReport(array $queryParams=[])
    {
        $params = (!empty($queryParams)) ? '?'.http_build_query($queryParams).'&' : '';
        $uri = self::API_ENDPOINT.'reports/daily_peak_hours'.$params;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 5
     * @param array $queryParams
     * @return bool|string
     */
    public function getWorkunitsDailyReport(array $queryParams=[])
    {
        $params = (!empty($queryParams)) ? '?'.http_build_query($queryParams).'&' : '';
        $uri = self::API_ENDPOINT.'reports/wu_daily_report'.$params;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 5
     * @param array $queryParams
     * @return bool|string
     */
    public function getPayrollReport(array $queryParams=[])
    {
        $params = (!empty($queryParams)) ? '?'.http_build_query($queryParams).'&' : '';
        $uri = self::API_ENDPOINT.'payroll/report'.$params;
        return $this->getResponse($uri);
    }


    /**
     ******************************************************************
     ***************** EMPLOYEES CLOCKIN/OUT Functions ****************
     ******************************************************************
     */

    /**
     * Permission level: 3
     * @return bool|string
     */
    public function getPreclockins()
    {
        $uri = self::API_ENDPOINT.'preclockins';
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 5
     * @param $id
     * @return bool|string
     */
    public function getEmployeesPreclockin($id)
    {
        $uri = self::API_ENDPOINT.'employees/'.$id.'/preclockin';
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 5
     * @param $employeeId
     * @return mixed
     */
    public function postEmployeesPreclockin($employeeId)
    {
        $uri = self::API_ENDPOINT.'employees/'.$employeeId.'/preclockin';
        return $this->postResponse($uri);
    }

    /**
     * Permission level: 5
     * @param $employeeId
     * @param array $data
     * @return mixed
     */
    public function postEmployeeClockin($employeeId,array $data=[])
    {
        $uri = self::API_ENDPOINT.'employees/'.$employeeId.'/clockin';
        return $this->postResponse($uri,$data);
    }

    /**
     * Permission level: 5
     * @param $employeeId
     * @param array $data
     * @return mixed
     */
    public function putEmployeeClockout($employeeId,array $data=[])
    {
        $uri = self::API_ENDPOINT.'employees/'.$employeeId.'/clockout';
        return $this->putResponse($uri,$data);
    }


    /**
     ******************************************************************
     ************************ SKILLS Functions ************************
     ******************************************************************
     */

    /**
     * Permission level: 7
     * @return bool|string
     */
    public function getSkills()
    {
        $uri = self::API_ENDPOINT.'skills';
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 5
     * @param $id
     * @return bool|string
     */
    public function getSkill($id)
    {
        $uri = self::API_ENDPOINT.'skills/'.$id;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 5
     * @param array $data
     * @return mixed
     */
    public function postSkills(array $data=[])
    {
        $uri = self::API_ENDPOINT.'skills';
        return $this->postResponse($uri,$data);
    }

    /**
     * Permission level: 5
     * @param $id
     * @param array $data
     * @return mixed
     */
    public function putSkill($id,array $data=[])
    {
        $uri = self::API_ENDPOINT.'skills/'.$id;
        return $this->putResponse($uri,$data);
    }

    /**
     * Permission level: 5
     * @param $id
     * @return mixed
     */
    public function deleteSkill($id)
    {
        $uri = self::API_ENDPOINT.'/skills/'.$id;
        return $this->deleteResponse($uri);
    }


    /**
     ******************************************************************
     ********************* CUSTOM FIELDS Functions ********************
     ******************************************************************
     */

    /**
     * Permission level: 7
     * @param array $queryParams
     * @return bool|string
     */
    public function getCustomFields(array $queryParams=[])
    {
        $params = (!empty($queryParams)) ? '?'.http_build_query($queryParams).'&' : '';
        $uri = self::API_ENDPOINT.'customfields';
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 5
     * @param $id
     * @return bool|string
     */
    public function getCustomField($id)
    {
        $uri = self::API_ENDPOINT.'customfields/'.$id;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 3
     * @param array $data
     * @return mixed
     */
    public function postCustomFields(array $data=[])
    {
        $uri = self::API_ENDPOINT.'customfields';
        return $this->postResponse($uri,$data);
    }

    /**
     * Permission level: 3
     * @param $id
     * @param array $data
     * @return mixed
     */
    public function putCustomFields($id,array $data=[])
    {
        $uri = self::API_ENDPOINT.'customfields/'.$id;
        return $this->putResponse($uri,$data);
    }

    /**
     * Permission level: 3
     * @param $id
     * @return mixed
     */
    public function deleteCustomField($id)
    {
        $uri = self::API_ENDPOINT.'/customfields/'.$id;
        return $this->deleteResponse($uri);
    }


    /**
     ******************************************************************
     ************************* NOTES Functions ************************
     ******************************************************************
     */

    /**
     * Permission level: 7
     * @param array $data
     * @return mixed
     */
    public function getNotes(array $data=[])
    {
        $uri = self::API_ENDPOINT.'notes';
        return $this->postResponse($uri,$data);
    }

    /**
     * Permission level: 7
     * @param $id
     * @return bool|string
     */
    public function getNote($id)
    {
        $uri = self::API_ENDPOINT.'notes/'.$id;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: 7
     * @param array $data
     * @return mixed
     */
    public function postNote(array $data=[])
    {
        $uri = self::API_ENDPOINT.'notes';
        return $this->postResponse($uri,$data);
    }

    /**
     * Permission level: 7
     * @param $id
     * @param array $data
     * @return mixed
     */
    public function putNote($id,array $data=[])
    {
        $uri = self::API_ENDPOINT.'notes/'.$id;
        return $this->putResponse($uri,$data);
    }

    /**
     * Permission level: 7
     * @param $id
     * @return mixed
     */
    public function deleteNote($id)
    {
        $uri = self::API_ENDPOINT.'/notes/'.$id;
        return $this->deleteResponse($uri);
    }


    /**
     ******************************************************************
     *********************** DASHBOARD Functions **********************
     ******************************************************************
     */

    /**
     * Permission level: 5
     * @param array $queryParams
     * @return bool|string
     */
    public function getOnnow(array $queryParams=[])
    {
        $params = (!empty($queryParams)) ? '?'.http_build_query($queryParams).'&' : '';
        $uri = self::API_ENDPOINT.'dashboard/onnow'.$params;
        return $this->getResponse($uri);
    }


    /**
     ******************************************************************
     ******************** GROUP ACCOUNTS Functions ********************
     ******************************************************************
     */

    /**
     * Permission level: 3
     * @param array $queryParams
     * @return bool|string
     */
    public function getGroupReports(array $queryParams=[])
    {
        $params = (!empty($queryParams)) ? '?'.http_build_query($queryParams).'&' : '';
        $uri = self::API_ENDPOINT.'groupaccounts/reports/'.$params;
        return $this->getResponse($uri);
    }


    /**
     ******************************************************************
     ********************* AVAILABILITY Functions *********************
     ******************************************************************
     */

    /**
     * Permission level: ?
     * @param array $data
     * @return mixed
     */
    public function getAvailabilityInDatePeriod(array $data=[])
    {
        $uri = self::API_ENDPOINT.'employees/availability';
        return $this->postResponse($uri,$data);
    }

    /**
     * Permission level: ?
     * @param array $queryParams
     * @return bool|string
     */
    public function getSingleAvailabilitySlot(array $queryParams=[])
    {
        $params = (!empty($queryParams)) ? '?'.http_build_query($queryParams).'&' : '';
        $uri = self::API_ENDPOINT.'employees/availability/'.$params;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: ?
     * @param array $data
     * @return mixed
     */
    public function getMultiple(array $data=[])
    {
        $uri = self::API_ENDPOINT.'employees/availability/multiple';
        return $this->postResponse($uri,$data);
    }

    /**
     * Permission level: ?
     * @param $seriesId
     * @param array $queryParams
     * @return bool|string
     */
    public function getAvailabilityBySeries($seriesId,array $queryParams=[])
    {
        $params = (!empty($queryParams)) ? '?'.http_build_query($queryParams).'&' : '';
        $uri = self::API_ENDPOINT.'employees/availability/series/'.$seriesId.'/slots'.$params;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: ?
     * @param array $data
     * @return mixed
     */
    public function createSingleAvailability(array $data=[])
    {
        $uri = self::API_ENDPOINT.'employees/availability/create';
        return $this->postResponse($uri,$data);
    }

    /**
     * Permission level: ?
     * @param array $data
     * @return mixed
     */
    public function createAvailabilitySeries(array $data=[])
    {
        $uri = self::API_ENDPOINT.'employees/availability/series';
        return $this->postResponse($uri,$data);
    }

    /**
     * Permission level: ?
     * @param array $data
     * @return mixed
     */
    public function approveAvailability(array $data=[])
    {
        $uri = self::API_ENDPOINT.'employees/availability/approve';
        return $this->postResponse($uri,$data);
    }

    /**
     * Permission level: ?
     * @param array $data
     * @return mixed
     */
    public function rejectAvailability(array $data=[])
    {
        $uri = self::API_ENDPOINT.'employees/availability/reject';
        return $this->postResponse($uri,$data);
    }

    /**
     * Permission level: ?
     * @param $seriesId
     * @param array $data
     * @return mixed
     */
    public function rejectSeries($seriesId,array $data=[])
    {
        $uri = self::API_ENDPOINT.'employees/availability/reject/'.$seriesId;
        return $this->postResponse($uri,$data);
    }

    /**
     * Permission level: ?
     * @param $seriesId
     * @param array $data
     * @return mixed
     */
    public function deleteSeries($seriesId,array $data=[])
    {
        $uri = self::API_ENDPOINT.'/employees/availability/series/'.$seriesId;
        return $this->deleteResponse($uri,$data);
    }


    /**
     ******************************************************************
     ************** DEMAND-DRIVEN SCHEDULING Functions ****************
     ******************************************************************
     */

    /**
     * Permission level: ?
     * @param array $queryParams
     * @return bool|string
     */
    public function getForecastDataPoints(array $queryParams=[])
    {
        $params = (!empty($queryParams)) ? '?'.http_build_query($queryParams).'&' : '';
        $uri = self::API_ENDPOINT.'forecasts/datapoints'.$params;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: ?
     * @param array $data
     * @return mixed
     */
    public function createForecastDataPoints(array $data=[])
    {
        $uri = self::API_ENDPOINT.'forecasts/datapoints';
        return $this->postResponse($uri,$data);
    }

    /**
     * Permission level: ?
     * @param array $data
     * @return mixed
     */
    public function updateForecastDataPoints(array $data=[])
    {
        $uri = self::API_ENDPOINT.'forecasts/datapoints';
        return $this->putResponse($uri,$data);
    }

    /**
     * Permission level: ?
     * @param array $data
     * @return mixed
     */
    public function createForecastDriver(array $data=[])
    {
        $uri = self::API_ENDPOINT.'forecasts/driver';
        return $this->postResponse($uri,$data);
    }

    /**
     * Permission level: ?
     * @param array $queryParams
     * @return bool|string
     */
    public function getForecastDriver(array $queryParams=[])
    {
        $params = (!empty($queryParams)) ? '?'.http_build_query($queryParams).'&' : '';
        $uri = self::API_ENDPOINT.'forecasts/driver'.$params;
        return $this->getResponse($uri);
    }

    /**
     * Permission level: ?
     * @return bool|string
     */
    public function getForecastDriversFromCompany()
    {
        $uri = self::API_ENDPOINT.'forecasts/drivers';
        return $this->getResponse($uri);
    }

    /**
     * Permission level: ?
     * @param $driverId
     * @return mixed
     */
    public function deleteForecastDriver($driverId)
    {
        $uri = self::API_ENDPOINT.'/forecasts/driver/'.$driverId;
        return $this->deleteResponse($uri);
    }







    /**
     ******************************************************************
     ********************** Private Functions *************************
     ******************************************************************
     */

    /**
     * @return void
     */
    private function authenticate(): void
    {
        try {
            $config = [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'grant_type' => 'password',
                'username' => $this->username,
                'password' => $this->password,
                'redirect_uri' => ($this->redirectUri) ? $this->redirectUri : null
            ];

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => self::API_OAUTH,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => http_build_query($config),
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded'
                ),
            ));

            $response = json_decode(curl_exec($curl));

            curl_close($curl);

            if (property_exists($response,'error')) {
                throw new \Exception($this->internalErrors(4,$response));
            }

            $this->accessToken = $response->access_token;
            $this->refreshToken = $response->refresh_token;

            //print_r($response);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @param $uri
     * @param array $data
     * @return mixed
     */
    private function deleteResponse($uri, array $data=[])
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL,$uri.'?access_token='.$this->accessToken.'&');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        $output = curl_exec($curl);

        curl_close($curl);

        return json_decode($output);
    }

    /**
     * @param $uri
     * @return bool|string
     */
    private function getResponse($uri)
    {
        $pos = strpos($uri,'?');

        // Added for strangely formatted multi param uri's
        if ($pos === false) {
            $token = '?access_token=';
        }else{
            $token = 'access_token=';
        }

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL,$uri.$token.$this->accessToken);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
        $output = curl_exec($curl);

        curl_close($curl);

        return json_decode($output);
    }

    /**
     * @param $errNo
     * @param null $response
     * @return string
     */
    private function internalErrors($errNo,$response=null): string
    {
        switch ($errNo) {
            case 1:
                return "You must provide all required parameters in order to authenticate against the Humanity API.";
            case 2:
                return "This SDK requires the PHP cURL extension to be installed.";
            case 3:
                return "This SDK requires the PHP JSON extension to be installed.";
            case 4:
                return $response->error_description;
            default:
                return "Could not find the requested error message.";
        }
    }

    /**
     * @param $uri
     * @param array $data
     * @return mixed
     */
    private function postResponse($uri,array $data=[])
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL,$uri.'?access_token='.$this->accessToken.'&');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        $output = curl_exec($curl);

        curl_close($curl);

        return json_decode($output);
    }

    /**
     * @param $uri
     * @param array $data
     * @return mixed
     */
    private function postResponseJson($uri,array $data=[])
    {
        $curl = curl_init();
        $payload = json_encode($data);

        curl_setopt($curl, CURLOPT_URL,$uri.'?access_token='.$this->accessToken.'&');
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json','Accept:application/json']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
        $output = curl_exec($curl);

        curl_close($curl);

        return json_decode($output);
    }

    /**
     * @param $uri
     * @param array $data
     * @return mixed
     */
    private function putResponse($uri,array $data=[])
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL,$uri.'?access_token='.$this->accessToken.'&');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        $output = curl_exec($curl);

        curl_close($curl);

        return json_decode($output);
    }

    /**
     * @return void
     */
    private function refreshAuthentication(): void
    {
        try {
            $config = [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'grant_type' => 'refresh_token',
                'refresh_token' => $this->refreshToken
            ];

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => self::API_OAUTH,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => http_build_query($config),
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded'
                ),
            ));

            $response = json_decode(curl_exec($curl));

            curl_close($curl);

            if (property_exists($response,'error')) {
                throw new \Exception($this->internalErrors(4,$response));
            }

            $this->accessToken = $response->access_token;
            $this->refreshToken = $response->refresh_token;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

}
