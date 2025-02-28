<?php
class Volume_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function createVolume($data) {
        return $this->db->insert('Volume', $data);
    }

    public function getVolumeById($volumeid) {
        return $this->db->get_where('Volume', array('volumeid' => $volumeid))->row();
    }

    public function updateVolume($volumeid, $data) {
        $this->db->where('volumeid', $volumeid);
        return $this->db->update('Volume', $data);
    }

    public function deleteVolume($volumeid) {
        // Delete the volume from the database
        $this->db->where('volumeid', $volumeid);
        $this->db->delete('Volume');
    }
    
    public function getAllVolumes() {
        $this->db->order_by('date_at', 'DESC');
        return $this->db->get('Volume')->result();
    }
    
    public function getVolumes() {
        $this->db->where('isArchive', 0);
        $this->db->order_by('date_published', 'DESC');
        return $this->db->get('Volume')->result_array();
    }
    
    public function getVolumesHome() {
        $this->db->where('isArchive', 0);
        $this->db->where('published', 1);
        $this->db->order_by('date_published', 'DESC'); 
        return $this->db->get('Volume')->result_array();
    }
    

    public function updatePublishedStatus($volumeid, $published) {
        $date_published = $published == 1 ? date('Y-m-d H:i:s') : null;
    
        $data = array(
            'published' => $published,
            'date_published' => $date_published
        );
    
        $this->db->where('volumeid', $volumeid);
        $this->db->update('Volume', $data);
    }
    public function archiveVolume($volumeid) {
        $this->db->where('volumeid', $volumeid);
        $this->db->update('volume', array('isArchive' => 1));
    
        return $this->db->affected_rows() > 0;
    }
    
    public function unArchiveVolume($volumeid) {
        $this->db->where('volumeid', $volumeid);
        $this->db->update('volume', array('isArchive' => 0));
    
        return $this->db->affected_rows() > 0;
    }

    public function get_all_volumes() {
        $query = $this->db->get_where('volume', array('isArchive' => 0, 'published' => 1));
        $this->db->order_by('date_published', 'DESC');
        return $query->result_array();
    }
    
    public function get_volume($volumeid) {
        $query = $this->db->get_where('volume', array('volumeid' => $volumeid, 'isArchive' => 0));
        return $query->row_array();
    } 

    public function get_archivedVolume($volumeid) {
        $query = $this->db->get_where('volume', array('volumeid' => $volumeid, 'isArchive' => 1));
        return $query->row_array();
    } 

    public function getArchivedVolumes() {
        $this->db->select('volumeid, vol_name, description, date_published');
        $this->db->from('volume');
        $this->db->where('isArchive', 1);
        $this->db->order_by('date_at', 'DESC');
    
        $query = $this->db->get();
        return $query->result_array(); 
    }
}
