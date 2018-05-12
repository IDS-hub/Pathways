using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class Popup : MonoBehaviour {
	public static Popup Instance = null;

	[SerializeField] GameObject singlePopup;
	[SerializeField] GameObject doublePopup;

	System.Action OnClickConfirm;
	System.Action OnClickCancel;

	[SerializeField] Text titleString_single;
	[SerializeField] Text descriptionString_single;

	[SerializeField] Text titleString_double;
	[SerializeField] Text descriptionString_double;

	void Awake(){
		Instance = this;
	}

	void Start () {
		singlePopup.SetActive(false);
		doublePopup.SetActive(false);
	}

	public void ShowPopup(string title, string desc, System.Action action){
		Loader.Instane.RemoveLoading();
		doublePopup.SetActive(false);
		singlePopup.SetActive(true);
		titleString_single.text = title;
		descriptionString_single.text = desc;
		OnClickConfirm = action;
	}

	public void ShowDoublePopup(string title, string desc, System.Action yes, System.Action no){
		Loader.Instane.RemoveLoading();
		singlePopup.SetActive(false);
		doublePopup.SetActive(true);
		titleString_double.text = title;
		descriptionString_double.text = desc;
		OnClickConfirm = yes;
		OnClickCancel = no;
	}
	
	public void OnClickOk(){
		if (OnClickConfirm != null)
			OnClickConfirm();
		OnClickConfirm = null;
		singlePopup.SetActive(false);
		doublePopup.SetActive(false);
	}

	public void OnClickNO(){
		if (OnClickCancel != null)
			OnClickCancel();
		OnClickCancel = null;
		singlePopup.SetActive(false);
		doublePopup.SetActive(false);
	}
}
