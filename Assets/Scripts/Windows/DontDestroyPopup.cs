using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class DontDestroyPopup : MonoBehaviour {

	static DontDestroyPopup _instance = null;

	void Awake(){
		if (_instance == null) {
			_instance = this;
			DontDestroyOnLoad(gameObject);
		} else {
			Destroy(gameObject);
		}
	}
}
