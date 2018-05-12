using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class SessionWork51 : SessionWork2 {

	void Start(){
		AVATAR_SHOW_TIME = 40f;
		AVATAR_SHOW_END = 135f;
	}

	public override void Reset() {
		
		if (ShowDecalAnim != null)
			ShowDecalAnim(false, 0);

		if (Character.DoPowerPose != null)
			Character.DoPowerPose(false);

		if (Character.HideCharacter != null)
			Character.HideCharacter(true, isAvatarShow);

		isAvatarShow = false;
	}

	public override void ShowAnim() {
		if (Character.HideCharacter != null)
			Character.HideCharacter(false, true);

		if (Character.DoPowerPose != null)
			Character.DoPowerPose(true);

		if (ShowDecalAnim != null)
			ShowDecalAnim(true, .0002f);

		//if (ShowDecalAnim != null)
			//ShowDecalAnim(true, AVATAR_SHOW_END - AVATAR_SHOW_TIME);
	}
}
