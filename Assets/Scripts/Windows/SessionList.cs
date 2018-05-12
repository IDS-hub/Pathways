using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using System.Linq;
using System;

public class SessionList : WindowController {

	[SerializeField] Transform contentPanel;

	[SerializeField] GameObject play_next_item_prefab;
	[SerializeField] GameObject session_history_item_prefab;

	public void OnClickMenu() {
		stateMachine.EnableLeftPanel(true);
	}

	public void OnEnable() {
		if (Character.HideCharacter != null)
			Character.HideCharacter(true, false);
	}

	public override void Start() {
		base.Start();

		Loader.Instane.ShowLoading();
		List<Session> sessionData = UserInfo.SessionList.OrderBy(o => int.Parse(o.id)).Reverse().ToList();
		for (int i = 0; i < sessionData.Count; i++) {
			if (!sessionData[i].isWatched)
				AddContent(play_next_item_prefab, sessionData[i]);
		}
		for (int i = 0; i < sessionData.Count; i++) {
			if (sessionData[i].isWatched)
				AddContent(session_history_item_prefab, sessionData[i]);
		}
		Loader.Instane.RemoveLoading();
			
	}

	private void AddContent(GameObject prefab, Session data) {
		GameObject temp = Instantiate(prefab) as GameObject;
		temp.transform.SetParent(contentPanel);

		RectTransform panel = (RectTransform)temp.transform;

		panel.transform.localPosition = Vector3.zero;
		temp.transform.localScale = Vector3.one;

		if (!temp.activeSelf)
			temp.SetActive(true);	

		if (data != null) {
			SessionContentPanel content = temp.GetComponent<SessionContentPanel>();
			if (content != null)
				content.SetSessionData(data);
		}
	}
}
