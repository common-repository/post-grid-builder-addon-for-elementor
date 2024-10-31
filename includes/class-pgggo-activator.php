<?php

// Firecd during plugin activaiotion


class Pgggo_Activator {

	public static function pgggo_activate(){
			//nothing since 1.0.0
			set_transient( 'pgggo-admin-notice-check-pro', true, 5 );
	}
}
