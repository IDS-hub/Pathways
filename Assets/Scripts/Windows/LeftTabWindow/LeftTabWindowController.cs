using UnityEngine.UI;
using System.Collections.Generic;
using UnityEngine;

public class LeftTabWindowController : WindowController {
	[SerializeField] Text nameLabel;
	[SerializeField] GameObject subscribeButton;
	[SerializeField] GameObject leftPanelParent;

	void OnEnable() {
		APIManager.OnProfileLoadComplete += OnProfileLoadComplete;
		LargeDataFeed.OnLandScape += OnLandScape;
	}

	void OnDisable(){
		APIManager.OnProfileLoadComplete -= OnProfileLoadComplete;
		LargeDataFeed.OnLandScape -= OnLandScape;
	}

	public void OnProfileButtonClicked() {
		HideLeftPanel();
		stateMachine.MoveToSelected(WindowPanels.ProfileWindow);
	}

	void OnLandScape(bool isLand){
		leftPanelParent.SetActive(!isLand);
	}

	void OnProfileLoadComplete(){
		nameLabel.text = UserInfo.FirstName + " " + UserInfo.LastName;
		Session next = UserInfo.NextSession;

		if(next != null && int.Parse(next.id) > 8 && !UserInfo.IsSubscribe)
			subscribeButton.SetActive(true);
		else
			subscribeButton.SetActive(false);

		subscribeButton.SetActive(true);
	}

	public void OnCloseButtonClicked() {
		HideLeftPanel();
	}

	public void OnSessionListButtonClicked() {
		HideLeftPanel();
		stateMachine.MoveToSelected(WindowPanels.SessionList);
	}

	public void OnHomeButtonClicked() {
		HideLeftPanel();
		stateMachine.MoveToSelected(WindowPanels.Home);
	}

	public void OnFeelGoodTasksButtonClicked() {
		HideLeftPanel();
		stateMachine.MoveToSelected(WindowPanels.FeelGoodTask);
	}

	public void OnStatisticsButtonClicked() {
		HideLeftPanel();
		stateMachine.LoadPopupUI(WindowPanels.Statistic);
	//	UnityEngine.SceneManagement.SceneManager.LoadScene(1);
	}

	public void OnPrivacyButtonClicked() {
		HideLeftPanel();
	}

	public void OnFAQClicked() {
		HideLeftPanel();
	}

	public void OnSubscribe(){
		HideLeftPanel();
		stateMachine.LoadPopupUI(WindowPanels.Subscription);
	}

	public void OnLogoutButtonClicked() {
		HideLeftPanel();
		DoLogout();
	}

	void HideLeftPanel() {
		stateMachine.EnableLeftPanel(false);
	}

	public void OnTerms(){
		HideLeftPanel();
		stateMachine.LoadPopupUI(WindowPanels.Terms);
	}

	public void OnPrivacy(){
		HideLeftPanel();
		stateMachine.LoadPopupUI(WindowPanels.Conditions);
	}
}
