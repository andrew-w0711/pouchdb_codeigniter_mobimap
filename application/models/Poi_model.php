<?php

class Poi_model extends CI_Model {

        public $id;
        public $project;
        public $name;
        public $lat;
        public $lon;
        public $icon;
	      public $number = 0;
	      public $address1 ="";
	      public $address2 = "";
	       public $city ="";
	        public $state ="";
	         public $zip ="";
	          public $phone1 ="";
	           public $phone2 ="";
	            public $fax ="";
        public $visible = 1;

        public function __construct()
        {
                // Call the CI_Model constructor
                parent::__construct();
        }

        public function get_last_ten_entries()
        {
                $query = $this->db->get('entries', 10);
                return $query->result();
        }

        public function get_poi_project_array($projects = array())
        {
            if (count($projects) > 0) {
                //var_dump($projects);
                $sql = 'SELECT * FROM poi WHERE project IN ?';
                $query = $this->db->query($sql, array($projects));
                return $query->result();
            }
        }

	    /*public function get_poi_project($id)
	    {
		    $sql = 'SELECT * FROM poi WHERE project = ?';
            $query = $this->db->query($sql, array($id));
            return $query->result();
	    }*/

        public function import($data)
        {
            if (isset($data['lat'])) { $data['lat'] = round($data['lat'],5); }
            if (isset($data['lon'])) { $data['lon'] = round($data['lon'],5); }
            $data['visible'] = 1;
            $this->db->insert('poi', $data);
        }

        public function create($data)
        {
            if (isset($data['lat'])) { $data['lat'] = round($data['lat'],5); }
            if (isset($data['lon'])) { $data['lon'] = round($data['lon'],5); }
            $this->db->insert('poi', $data);
            return $this->db->insert_id();
        }

        public function delete($id)
        {
          $this->db->query('DELETE FROM poi WHERE id = ? LIMIT 1',array($id));
          return array('status' => 'OK');
        }

        public function update($id,$data)
        {
            if (isset($data['lat'])) { $data['lat'] = round($data['lat'],5); }
            if (isset($data['lon'])) { $data['lon'] = round($data['lon'],5); }
            $this->db->where('id', $id);
            $this->db->limit(1);
            $this->db->update('poi', $data);
            return array('status' => 'OK', 'affected_rows' => $this->db->affected_rows());
        }

       	public function get_project_geojson($projectid = null) {
//    $data = array()
//    $data['type'] = 'FeatureCollection';
//    $data['features'] = array
		$query = $this->db->query('SELECT * FROM projects WHERE id = ? LIMIT 1',array($projectid));
		$row = $query->row();
        //$result = $row->user_client;
		$geojson = '{
  "type": "FeatureCollection",
  "name": "' . $row->name . '",
  "city": "' . $row->city . '",
  "state": "' . $row->state . '",
  "country": "' . $row->country . '",
  "features": [
';
		$sql = 'SELECT poi.*, icons.name AS icon_name, projects.name AS project_name
			FROM poi
			LEFT JOIN icons on icons.id = poi.icon
			LEFT JOIN projects ON projects.id = poi.project
            WHERE poi.project = ? AND poi.lat IS NOT NULL AND poi.lon IS NOT NULL AND poi.visible = 1';
		$query = $this->db->query($sql, array($projectid));
		$poi = array();
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$poi[] = '
    {
      "type": "Feature",
      "properties": {
	"name" : "' . $row->name . '",
	"phone1" : "' . $row->phone1 . '",
	"phone2" : "' . $row->phone2 . '",
	"address1" : "' . $row->address1 . '",
	"address2" : "' . $row->address2 . '",
	"city" : "' . $row->city . '",
	"state" : "' . $row->state . '",
	"icon_name" : "' . $row->icon_name . '",
	"id" : "' . $row->id . '",
    "uuid" : "' . $row->uuid . '",
      "title": "' . $row->name . '",
      "description": "Phone: ' . $row->phone1 . '<br>' . $row->address1 . '<br>' . $row->address2 . '<br>' . $row->city . ' ' . $row->state . '",
      "marker-color": "#fc4353",
      "marker-size": "medium",
      "marker-symbol": "' . $row->icon_name . '",
      "draggable": true
      },
      "geometry": {
        "type": "Point",
        "coordinates": [
          ' . $row->lon . ',
          ' . $row->lat . '
        ]
      }
    }';
			}
		}
		$geojson .= implode(',', $poi);
		$geojson .= '
]
}
';
		return $geojson;
	}

  public function data_from_csv($data) {
    $output_data = array();
    if (isset($data['project'])) { $output_data['project'] = $data['project']; };
    if (isset($data['name'])) { $output_data['name'] = $data['name']; };
    if (isset($data['lat'])) { $output_data['lat'] = $data['lat']; };
    if (isset($data['lon'])) { $output_data['lon'] = $data['lon']; };
    if (isset($data['icon'])) {	$output_data['icon'] = $data['icon']; };
    if (isset($data['number'])) {	$output_data['number'] = $data['number']; };
    if (isset($data['address1'])) { $output_data['address1'] = $data['address1']; };
    if (isset($data['address2'])) { $output_data['address2'] = $data['address2']; };
    if (isset($data['city'])) {	$output_data['city'] = $data['city']; };
    if (isset($data['state'])) { $output_data['state'] = $data['state']; };
    if (isset($data['zip'])) { $output_data['zip'] = $data['zip']; };
    if (isset($data['phone1'])) { $output_data['phone1'] = $data['phone1']; };
    if (isset($data['phone2'])) {	$output_data['phone2'] = $data['phone2']; };
    if (isset($data['fax'])) { $output_data['fax'] = $data['fax']; };
    return $output_data;
  }

  public function data_from_post() {
    $data = array();
    if ($this->input->post('project')) { $data['project'] = $this->input->post('project'); };
    if ($this->input->post('name')) { $data['name'] = $this->input->post('name'); };
    if ($this->input->post('lat')) { $data['lat'] = $this->input->post('lat'); };
    if ($this->input->post('lon')) { $data['lon'] = $this->input->post('lon'); };
    if ($this->input->post('icon')) {	$data['icon'] = $this->input->post('icon'); };
    if ($this->input->post('number')) {	$data['number'] = $this->input->post('number'); };
    if ($this->input->post('address1')) {	$data['address1'] = $this->input->post('address1'); };
    if ($this->input->post('address2')) { $data['address2'] = $this->input->post('address2'); };
    if ($this->input->post('city')) {	$data['city'] = $this->input->post('city'); };
    if ($this->input->post('state')) { $data['state'] = $this->input->post('state'); };
    if ($this->input->post('zip')) { $data['zip'] = $this->input->post('zip'); };
    if ($this->input->post('phone1')) { $data['phone1'] = $this->input->post('phone1'); };
    if ($this->input->post('phone2')) {	$data['phone2'] = $this->input->post('phone2'); };
    if ($this->input->post('fax')) { $data['fax'] = $this->input->post('fax'); };
    if ($this->input->post('visible')) { $data['visible'] = $this->input->post('visible'); };
    return $data;
  }

}
