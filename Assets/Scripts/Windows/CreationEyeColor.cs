using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class CreationEyeColor : MonoBehaviour {

	public static System.Action<int> OnPressEyeColor;

	[SerializeField] GameObject tick;

	void Start(){
		if (UserInfo.UserAvatar.eyeColorType == int.Parse(gameObject.name))
			tick.SetActive(true);
		else
			tick.SetActive(false);
	}

	void OnEnable(){
		CreationEyeColor.OnPressEyeColor += OnPress;
	}

	void OnDisable(){
		CreationEyeColor.OnPressEyeColor -= OnPress;
	}

	void OnPress(int id){
		if (id == int.Parse(gameObject.name)) {
			tick.SetActive(true);				
		} else {
			tick.SetActive(false);
		}
	}

	public void OnClickEyeColor(){
		if (OnPressEyeColor != null)
			OnPressEyeColor(int.Parse(gameObject.name));
	}
}
