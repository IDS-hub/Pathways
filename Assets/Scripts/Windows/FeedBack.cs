using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class FeedBack : WindowController {

	[SerializeField] InputField feedback;

	public void SendFeedBack(){
		if (feedback.text.Trim().Length > 0) {
			apiManager.SendFeedBack(feedback.text, null);
			Destroy(gameObject);
		}
		//Destroy(gameObject);
	}
	
	public void OnClickBack(){
		Destroy(gameObject);
	}
}
