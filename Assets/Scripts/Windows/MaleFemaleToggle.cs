using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class MaleFemaleToggle : MonoBehaviour {
	public static System.Action<bool> OnToggle;

	[SerializeField] GameObject toggle;
	[SerializeField] bool isFemale;

	void OnEnable(){
		MaleFemaleToggle.OnToggle += SetToggle;
	}

	void OnDisable(){
		MaleFemaleToggle.OnToggle -= SetToggle;
	}

	void Start() {
		SetToggle(UserInfo.UserAvatar.isFemale);
	}

	void SetToggle(bool flag){
		if (flag) {
			toggle.SetActive(isFemale);
		} else {
			toggle.SetActive(!isFemale);
		}
	}
	
	public void OnClickToggle(){
		if (OnToggle != null)
			OnToggle(isFemale);
	}
}
