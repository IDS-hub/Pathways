using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class SessionContentPanel : WindowController {

	[SerializeField] Text header;
	[SerializeField] Text desc;

	Session data;

	// setting data to session list items
	public void SetSessionData(Session data) {
		this.data = data;
		header.text = string.Format("{0}. {1}", data.id, data.title);
		desc.text = data.session_description;
	}

	// playing session on click
	public void PlaySession() {
		UserInfo.CurrentSession = data;
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
			
			if (data.isWatched)
				return false;

			if (int.Parse(data.id) <= Constants.PURCHASE_SESSION_ID)
				return false;

			for (int i = 0; i < UserInfo.SessionList.Count; i++) {
				if (int.Parse(UserInfo.SessionList[i].id) == Constants.PURCHASE_SESSION_ID && UserInfo.SessionList[i].isWatched)
					return true;
			}

			return false;
		}
	}
}
