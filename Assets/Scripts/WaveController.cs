using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class WaveController : MonoBehaviour {
	public static System.Action OnStart;

	[SerializeField] GameObject[] waves;


	void Start () {
		Invoke("StartWave", 1f);
	}

	void StartWave(){
		if (OnStart != null)
			OnStart();
		for(int i = 0; i < waves.Length; i ++){
		//	waves[i].SetActive(true);
		}
	}
	

}
