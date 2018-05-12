using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class FeelGoodTask : WindowController {

	[SerializeField] Transform contentPanel;
	[SerializeField] GameObject feed_good_task_prefab;

	public void OnClickMenu() {
		stateMachine.EnableLeftPanel(true);
	}

	public void OnEnable() {
		if (Character.HideCharacter != null)
			Character.HideCharacter(true, false);
	}

	public override void Start() {
		base.Start();


		TextAsset goodtaskList = Resources.Load<TextAsset>("feel_good_session");
		MiniJsonArray goodtaskListArray = new MiniJsonArray(goodtaskList.text);

		// getting the list of feel good task completed by users
		if (UserInfo.SessionList.Count > 1) {
			Loader.Instane.ShowLoading();
			for (int i = 0; i < UserInfo.SessionList.Count; i++) {
				for (int j = 0; j < goodtaskListArray.Count; j++) {
					if (UserInfo.SessionList[i].id == goodtaskListArray.Get(j).GetField("session_id", "")) {
						AddContent(j, goodtaskListArray.Get(j), UserInfo.SessionList[i]);
					}
				}
			}
		}

		Loader.Instane.RemoveLoading();
	}

	private void AddContent(int id, MiniJsonObject data, Session sessionData) {
		GameObject temp = Instantiate(feed_good_task_prefab) as GameObject;
		temp.transform.SetParent(contentPanel);

		RectTransform panel = (RectTransform)temp.transform;

		panel.transform.localPosition = Vector3.zero;
		temp.transform.localScale = Vector3.one;

		if (!temp.activeSelf)
			temp.SetActive(true);	

		FeelGoodTaskItem content = temp.GetComponent<FeelGoodTaskItem>();
		if (content != null)
			content.SetFeelGoodData(id, data, sessionData);

	}
}
