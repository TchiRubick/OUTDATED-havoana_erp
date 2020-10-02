<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . "/interface/Device_interface.php";

class Device_model extends CI_Model implements IDevice
{
    private $_db;
    public $_errorMessage = "";

    public function conn($config)
    {
        $this->_db = $this->load->database($config, TRUE);
    }

    public function getAll()
    {
        $result = [];

        try {
            $query = $this->_db->query(IDevice::QUERY_SELECT_ALL_FOR_GRID);

            if (!$query) {
                throw new Exception();
            } else {
                $result = $query->result_array();
            }
        } catch (Exception $e) {
            log_message('ERROR', 'Device_model: getAll() : ' . IDevice::QUERY_SELECT_ALL_FOR_GRID . ' ' . $e->getMessage());
        }

        return $result;
    }

    public function switchDevice($ide, $limit)
    {
        $result = true;

        try {
            $queryGet = $this->_db->query(IDevice::QUERY_GET_STATUT_BY_IDE, [$ide]);

            if (!$queryGet) {
                throw new Exception("Appareil non trouvée");
            } else {
                $row = $queryGet->result_array();

                switch ($row[0]['dvc_statut']) {
                    case 'ACT':
                        $newState = "INA";
                        break;
                    case 'TEN':
                        $newState = "ACT";
                        break;
                    case 'INA':
                        $newState = "ACT";
                        break;
                    default:
                        $newState = "INA";
                        break;
                }

                if ($newState === 'ACT') {
                    $queryLimit = $this->_db->query(IDevice::QUERY_COUNT_DEVICE_BY_STATE, [$newState]);

                    if (!$queryLimit) {
                        throw new Exception("Configuration introuvable, Veuillez contacter un responsable");
                    }

                    $rowLimit = $queryLimit->result_array();

                    if ($rowLimit[0]['nombre'] >= $limit) throw new Exception("Limite d'appreil atteint, veuillez désactiver un appareil pour pouvoir activer celui ci");
                }

                $query = $this->_db->query(IDevice::QUERY_UPDATE_STATE_BY_IDE, [$newState, $ide]);

                if (!$query) {
                    throw new Exception();
                }
            }
        } catch (Exception $e) {
            $this->_errorMessage = $e->getMessage();
            log_message('ERROR', 'Device_model: switchDevice() : ' . IDevice::QUERY_GET_STATUT_BY_IDE . ' ' . IDevice::QUERY_UPDATE_STATE_BY_IDE . " " . $e->getMessage());
            $result = false;
        }

        return $result;
    }

    public function setName(array $arg)
    {
        $result = true;

        try {
            $query = $this->_db->query(IDevice::QUERY_UPDATE_NOM_BY_IDE, $arg);

            if (!$query) {
                throw new Exception();
            }
        } catch (Exception $e) {
            $this->_errorMessage = $e->getMessage();
            log_message('ERROR', 'Device_model: setName() : ' . IDevice::QUERY_UPDATE_NOM_BY_IDE . ' ' . $e->getMessage());
            $result = false;
        }

        return $result;
    }

    public function getUserMagByIde($ide)
    {
        $result = [];

        $requete = " SELECT utl_idexterne, mag_code FROM tr_maguserdevice INNER JOIN sys_magasin ON mag_id = magudvc_mag INNER JOIN sys_utilisateur ON magudvc_user = utl_idexterne WHERE magudvc_device = ? ";

        try {
            $query = $this->_db->query($requete, [$ide]);

            if (!$query) {
                throw new Exception();
            }

            $result = $query->row_array();
        } catch (Exception $e) {
            $this->_errorMessage = $e->getMessage();
            log_message('ERROR', 'Device_model: getUserMagByIde() : ' . $requete . ' { ' . json_encode([$ide]) . ' } ' . $e->getMessage());
        }

        return $result;
    }

    public function setListeUser($user, $device, $mag)
    {
        $result = true;

        $requete = " INSERT IGNORE INTO tr_maguserdevice ( magudvc_device, magudvc_user, magudvc_mag) VALUES ( ? , ? , ?)";
        try {
            $query = $this->_db->query($requete, [$device, $user, $mag]);

            if (!$query) {
                throw new Exception();
            }
        } catch (Exception $e) {
            $this->_errorMessage = $e->getMessage();
            log_message('ERROR', 'Device_model: setName() : ' . $requete . ' { ' . json_encode([$device, $user, $mag]) . '} ' . $e->getMessage());
            $result = false;
        }

        return $result;
    }

    public function deleteDroitByIde($ide)
    {
        $result = true;

        $requete = " DELETE FROM tr_maguserdevice WHERE magudvc_device = ?";

        try {
            $query = $this->_db->query($requete, [$ide]);

            if (!$query) {
                throw new Exception();
            }
        } catch (Exception $e) {
            $this->_errorMessage = $e->getMessage();
            log_message('ERROR', 'Device_model: deleteDroitByIde() : ' . $requete . ' { ' . json_encode([$ide]) . '} ' . $e->getMessage());
            $result = false;
        }

        return $result;
    }

    public function deleteDevice($ide)
    {
        $result = true;

        $requete = " DELETE t_devices, tr_maguserdevice FROM t_devices INNER JOIN tr_maguserdevice ON tr_maguserdevice.magudvc_device = t_devices.dvc_idexterne WHERE dvc_idexterne = ? ";

        try {
            $query = $this->_db->query($requete, [$ide]);

            if (!$query) {
                throw new Exception();
            }
        } catch (Exception $e) {
            $this->_errorMessage = $e->getMessage();
            log_message('ERROR', 'Device_model: deleteDevice() : ' . $requete . ' { ' . json_encode([$ide]) . ' } ' . $e->getMessage());
            $result = false;
        }

        return $result;
    }
}
