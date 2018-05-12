using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class Loader : MonoBehaviour {

	public static Loader Instane = null;
	[SerializeField] GameObject showLoading;
	[SerializeField] Text progressText;

	void Awake(){
		Instane = this;
	}

	void Start () {
		progressText.text = "";
		showLoading.SetActive(false);
	}
	
	public void ShowLoading(){
		progressText.text = "";
		showLoading.SetActive(true);
	}

	public void RemoveLoading(){
		progressText.text = "";
		showLoading.SetActive(false);
	}

	public void ShowProgress(int progress){
		progressText.text = string.Format("{0}%", progress);
	}
}
