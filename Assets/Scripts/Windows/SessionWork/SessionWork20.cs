using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class SessionWork20 : SessionWork2 {

	//public static System.Action<bool, float> ShowDecalAnim;

	void Start(){
		AVATAR_SHOW_TIME = 101f;
		AVATAR_SHOW_END = 213f;
	}

	public override void Reset() {
		
		if (ShowDecalAnim != null)
			ShowDecalAnim(false, 0);

		if (Character.HideCharacter != null)
			Character.HideCharacter(true, isAvatarShow);

		isAvatarShow = false;
	}

	public override void ShowAnim() {
		if (Character.HideCharacter != null)
			Character.HideCharacter(false, true);

		if (ShowDecalAnim != null)
			ShowDecalAnim(true, AVATAR_SHOW_END - AVATAR_SHOW_TIME);
	}
}
