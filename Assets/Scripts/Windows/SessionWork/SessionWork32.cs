using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class SessionWork32 : MonoBehaviour {

	public static System.Action<bool, string, TimeManagement> ShowText;

	protected float AVATAR_SHOW_TIME = 235f;
	protected float AVATAR_SHOW_END = 270f;

	protected bool isTextShow = false;

	string showString = "Spend 10 minutes writing a list of negative past and current experiences, including pressures, as far back as you can remember. Then the final 10 minutes writing about how the most significant of these events or pressures make you feel. To finish, take great satisfaction in ripping up this list.";
	float nowTime = 0f;

	void OnEnable() {
		AudioPlayer.OnUpdateTime += OnUpdateTime;
	}

	void OnDisable() {
		AudioPlayer.OnUpdateTime -= OnUpdateTime;

		Reset();
	}

	void OnUpdateTime(float time, bool byUser) {
		//Debug.Log("OnUpdateTime " + time);
		if(byUser)
			Debug.Log("by user" + byUser);
		nowTime = time;
		if (time >= AVATAR_SHOW_TIME && time <= AVATAR_SHOW_END + 1) {
			if (byUser) {
				isTextShow = false;
			}
			Show();
		} else
			Reset();
	}

	public virtual void Reset() {
		if (isTextShow) {
			if (ShowText != null)
				ShowText(false, "", null);
			isTextShow = false;
		}
	}

	public virtual void Show() {
		if (!isTextShow) {
			if (ShowText != null)
				ShowText(true, showString, new TimeManagement(AVATAR_SHOW_TIME, AVATAR_SHOW_END + 1, nowTime));

			isTextShow = true;
		}
	}
}
