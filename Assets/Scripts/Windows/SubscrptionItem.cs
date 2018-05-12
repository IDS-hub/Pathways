using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class SubscrptionItem : MonoBehaviour {
	public static System.Action<SubscriptionType, string> OnSelectSubscriptonType;

	[SerializeField] GameObject selected;
	[TextArea(7, 10)][SerializeField] string subcriptionText;
	[SerializeField] SubscriptionType type;

	public enum SubscriptionType {
		ONE_MONTH,
		ONE_YEAR,
		LIFETIME
	}

	void Start(){
		if (type == SubscriptionType.ONE_MONTH)
			OnClick();
	}
		
	void OnEnable(){
		OnSelectSubscriptonType += SelectSubscriptonType;
	}

	void OnDisable(){
		OnSelectSubscriptonType -= SelectSubscriptonType;
	}

	void SelectSubscriptonType(SubscriptionType select, string text){
		if (select == type)
			selected.SetActive(true);
		else
			selected.SetActive(false);
	}

	public void OnClick(){
		if (OnSelectSubscriptonType != null)
			OnSelectSubscriptonType(type, subcriptionText);
	}
}
