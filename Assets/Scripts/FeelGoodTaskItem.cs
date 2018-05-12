using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

// feel good task wrapper class
public class FeelGoodTaskItem : WindowController {

	[SerializeField] Text header;
	[SerializeField] Text desc;
	[SerializeField] Image background;
	[SerializeField] Sprite even, odd;

	Session sessionData;

	public void SetFeelGoodData(int id, MiniJsonObject data, Session sess_data) {
		sessionData = sess_data;

		header.text = data.GetField("title", "");
		desc.text = data.GetField("description", "");

		if (id % 2 == 0)
			background.sprite = even;
		else
			background.sprite = odd;
	}

	public void OnClickReplay() {
		UserInfo.CurrentSession = sessionData;
		if (ShouldShowSubscribtion) {
			stateMachine.LoadPopupUI(WindowPanels.Subscription);
		} else {
			if (UserInfo.IsQuizSession)
				stateMachine.MoveToSelected(WindowPanels.Quiz);
			else
				stateMachine.MoveToSelected(WindowPanels.Session);
		}	
	}

	// checking whether subscription window should show or not
	bool ShouldShowSubscribtion {
		get { 
			if (UserInfo.IsSubscribe)
				return false;

			if (sessionData.isWatched)
				return false;

			if (int.Parse(sessionData.id) <= Constants.PURCHASE_SESSION_ID)
				return false;

			for (int i = 0; i < UserInfo.SessionList.Count; i++) {
				if (int.Parse(UserInfo.SessionList[i].id) == Constants.PURCHASE_SESSION_ID && UserInfo.SessionList[i].isWatched)
					return true;
			}

			return false;
		}
	}

}
