using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class CanvasManager : MonoBehaviour {

	[SerializeField] CanvasScaler canvasScaler;


	void OnEnable(){
		LargeDataFeed.OnLandScape += OnLandScape;
	}

	void OnDisable(){
		LargeDataFeed.OnLandScape -= OnLandScape;
	}

	void OnLandScape(bool isLandscape){
		if (isLandscape) {
			Screen.orientation = ScreenOrientation.Landscape;
			canvasScaler.referenceResolution = new Vector2(1920, 1080);
			canvasScaler.matchWidthOrHeight = 1;
		} else {
			Screen.orientation = ScreenOrientation.Portrait;
			canvasScaler.referenceResolution = new Vector2(1080, 1920);
			canvasScaler.matchWidthOrHeight = 0;
		}
	}
}
