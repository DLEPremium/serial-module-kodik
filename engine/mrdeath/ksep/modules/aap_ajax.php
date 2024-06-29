<?php 

if( !defined( 'DATALIFEENGINE' ) ) {
	header( "HTTP/1.1 403 Forbidden" );
	header ( 'Location: ../../' );
	die( "Hacking attempt!" );
}

if ( file_exists(ENGINE_DIR . '/mrdeath/aaparser/data/config.php') ) {
    if ( $shikiid ) $kodik_material_api = request($kodik_api_domain."search?token=".$kodik_apikey."&shikimori_id=".$shikiid."&with_episodes_data=true");
    elseif ( $mdlid ) $kodik_material_api = request($kodik_api_domain."search?token=".$kodik_apikey."&mdl_id=".$mdlid."&with_episodes_data=true");
    
    if ( $kodik_material_api['results'] ) {
        $ksep_arr = [];
        foreach ( $kodik_material_api['results'] as $material_result ) {
            foreach ( $material_result['seasons'] as $snum => $material_season ) {
                foreach ( $material_season['episodes'] as $ep_num => $material_episode ) {
                    $ksep_arr[$snum][$ep_num]['players'][$material_result['translation']['title']] = $material_episode['link'].'?season='.$snum.'&episode='.$ep_num.'&only_translations='.$material_result['translation']['id'].'&hide_selectors=true';
                    if ( !isset( $ksep_arr[$snum][$ep_num]['kadr1'] ) && isset($material_episode['screenshots'][0]) ) $ksep_arr[$snum][$ep_num]['kadr1'] = $material_episode['screenshots'][0];
                    if ( !isset( $ksep_arr[$snum][$ep_num]['kadr2'] ) && isset($material_episode['screenshots'][1]) ) $ksep_arr[$snum][$ep_num]['kadr2'] = $material_episode['screenshots'][1];
                    if ( !isset( $ksep_arr[$snum][$ep_num]['kadr3'] ) && isset($material_episode['screenshots'][2]) ) $ksep_arr[$snum][$ep_num]['kadr3'] = $material_episode['screenshots'][2];
                    if ( !isset( $ksep_arr[$snum][$ep_num]['kadr4'] ) && isset($material_episode['screenshots'][3]) ) $ksep_arr[$snum][$ep_num]['kadr4'] = $material_episode['screenshots'][3];
                    if ( !isset( $ksep_arr[$snum][$ep_num]['kadr5'] ) && isset($material_episode['screenshots'][4]) ) $ksep_arr[$snum][$ep_num]['kadr5'] = $material_episode['screenshots'][4];
                }
            }
        }
        
        $material_title = totranslit_it($kodik_material_api['results'][0]['title_orig'], true, false);
        $sez_count = $ep_count = 0;
        
        if ( $ksep_arr ) {
            
            ksort($ksep_arr);
            $sez_count = count($ksep_arr);
            foreach ( $ksep_arr as $snm => $eps ) {
                ksort($ksep_arr[$snm]);
                $ep_count = $ep_count + count($ksep_arr[$snm]);
            }
            
            $result_work = array(
		        'news_id' => $rowid,
		        'eps_list' => $ksep_arr,
		        'ep_count' => $ep_count,
		        'sez_count' => $sez_count,
		        'material_title' => $material_title
	        );
	        $result = json_encode($result_work);
	        echo $result;
        }
        else {
            die(json_encode(array(
	            'error' => 'Не не получили данные от Kodik'
	        )));
        }
        
    }
    else {
        die('empty');
    }
}