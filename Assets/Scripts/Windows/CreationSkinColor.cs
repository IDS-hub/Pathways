using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class CreationSkinColor : MonoBehaviour {

	public static System.Action<int> OnPressSkinColor;

	[SerializeField] GameObject tick;

	void Start(){
		if (UserInfo.UserAvatar.skinColorType == int.Parse(gameObject.name))
			tick.SetActive(true);
		else
			tick.SetActive(false);
	}

	void OnEnable(){
		CreationSkinColor.OnPressSkinColor += OnPress;
	}

	void OnDisable(){
		CreationSkinColor.OnPressSkinColor -= OnPress;
	}

	void OnPress(int id){
		if (id == int.Parse(gameObject.name)) {
			tick.SetActive(true);				
		} else {
			tick.SetActive(false);
		}
	}

	public void OnClickSkinColor(){
		if (OnPressSkinColor != null)
			OnPressSkinColor(int.Parse(gameObject.name));
	}
}
