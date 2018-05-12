using System;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.EventSystems;

public class AudioSlider : UnityEngine.UI.Slider {
	public bool isUserAction;

	public event Action OnUserPointerDown;
	public event Action OnUserPointerUp;

	public override void OnPointerDown(PointerEventData eventData) {
		if (AudioPlayer.IS_AUDIO_PAUSED)
			return;
		
		if (OnUserPointerDown != null)
			OnUserPointerDown();
        
		isUserAction = true;
		base.OnPointerDown(eventData);   
	}

	public override void OnPointerUp(PointerEventData eventData) { 

		if (AudioPlayer.IS_AUDIO_PAUSED)
			return;
		
		base.OnPointerUp(eventData);

		isUserAction = false;
		if (OnUserPointerUp != null)
			OnUserPointerUp();
	}
}
