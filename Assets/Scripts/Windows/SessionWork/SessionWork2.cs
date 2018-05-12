using System.Collections;
using System.Collections.Generic;
using UnityEngine;

// session 2 related work. This class mainly deals with Avatar related animations
public class SessionWork2 : MonoBehaviour {
	public static System.Action<bool, float> ShowDecalAnim;

	protected float AVATAR_SHOW_TIME = 467f;
	protected float AVATAR_SHOW_END = 494f;

	protected bool isAvatarShow = false;

	void OnEnable() {
		AudioPlayer.OnUpdateTime += OnUpdateTime;
	}

	void OnDisable() {
		AudioPlayer.OnUpdateTime -= OnUpdateTime;
		Reset();
	}

	void OnUpdateTime(float time, bool byUser) {

		if (byUser) {
			isAvatarShow = false;
			Reset();

			if (time >= AVATAR_SHOW_TIME && time <= AVATAR_SHOW_END) {
				if (AudioPlayer.ResetTimeLine != null)
					AudioPlayer.ResetTimeLine(AVATAR_SHOW_TIME - AudioPlayer.RESET_TIME);
			}
			
			return;
		}

		if (time >= AVATAR_SHOW_END) {
			if (isAvatarShow)
				Reset();

			isAvatarShow = false;

		} else if (time >= AVATAR_SHOW_TIME) {
			if (!isAvatarShow) {
				ShowAnim();
			}
			isAvatarShow = true;				
		} else
			isAvatarShow = false;
	}

	public virtual void Reset() {
		if (ShowDecalAnim != null)
			ShowDecalAnim(false, 0);
		
		if (Character.HideCharacter != null)
			Character.HideCharacter(true, isAvatarShow);
		
		isAvatarShow = false;
	}

	public virtual void ShowAnim() {
		if (Character.HideCharacter != null)
			Character.HideCharacter(false, true);

		if (ShowDecalAnim != null)
			ShowDecalAnim(true, AVATAR_SHOW_END - AVATAR_SHOW_TIME);
	}
}
