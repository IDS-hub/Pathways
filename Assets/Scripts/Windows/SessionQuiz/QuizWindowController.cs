using UnityEngine.UI;
using System.Collections.Generic;
using UnityEngine;

public class QuizWindowController : WindowController {
	
	[SerializeField] QuizLocalWindow quizWindow;
	[SerializeField] int nextWindowIndex;
	[SerializeField] Text OnButtonNextLabel;
	public List<QuizTask> tasks;
	public int currentTaskIndex = 0;

	private string nextText = "Next";
	private string finishText = "Finish Quiz";

	public override void Start() {
		base.Start();

		if (Character.HideCharacter != null)
			Character.HideCharacter(true, false);

		TextAsset dataAsset = Resources.Load<TextAsset>(string.Format("QuizData_{0}", UserInfo.CurrentSession.id));
		MiniJsonArray jsonArray = new MiniJsonArray(dataAsset.text);
		tasks = new List<QuizTask>();

		for (int i = 0; i < jsonArray.Count; i++) {
			MiniJsonObject dataObject = jsonArray.Get(i);
			tasks.Add(new QuizTask(dataObject.GetField("Question", ""), dataObject.GetField("CorrectAnswer", false), dataObject.GetField("Annotation", "")));
		}

		quizWindow.SetTask(tasks[currentTaskIndex]);
		SwitchTextLabelOnNextButton(false);
	}

	void OnEnable() {
		/*if (tasks == null)
			tasks = DataLoader.Instance.GetQuizTasks();

		quizWindow.SetTask(tasks[currentTaskIndex]);
		SwitchTextLabelOnNextButton(false);*/
	}

	public void NextButtonClicked() {
		if (currentTaskIndex == tasks.Count - 1) {

			Loader.Instane.ShowLoading();

			apiManager.PlaySession(UserInfo.CurrentSession.id, (temp, isSuccess) => {
				if (isSuccess) {
					UserInfo.CurrentSession.isWatched = true;
					if (UserInfo.NextSession == null) {
						apiManager.GetAllSession((data, gotSession) => {
							DoFeedback();
						}, APIManager.RESPONSE_SESSION);
					} else
						DoFeedback();
				} else {
					// user does not authenticated, logout
					Popup.Instance.ShowPopup(Constants.INVALID_SESSION_TITLE, Constants.INVALID_SESSION_DESC, () => {
						DoLogout();
					});
				}

			}, -1);				

			return;
		}

		currentTaskIndex++;
		quizWindow.SetTask(tasks[currentTaskIndex]);
	}

	void DoFeedback() {
		/*apiManager.CanGiveFeedback((json, success) => {
			Loader.Instane.RemoveLoading();
			SwitchTextLabelOnNextButton(true);
			stateMachine.EnableLeftPanel(true);
			if (success && json.GetField("cangive", false)) {
				stateMachine.MoveToSelected(WindowPanels.RatePainWindow);
			} else
				stateMachine.MoveToSelected(WindowPanels.ProfileWindow);
		});*/


		Loader.Instane.RemoveLoading();
		SwitchTextLabelOnNextButton(true);
		stateMachine.EnableLeftPanel(true);

		if(UserInfo.CurrentSession.session_summary.Length > 0)
			stateMachine.MoveToSelected(WindowPanels.RatePainWindow);
		else
			stateMachine.MoveToSelected(WindowPanels.Home);
	}

	void SwitchTextLabelOnNextButton(bool isFinish) {
		if (isFinish)
			OnButtonNextLabel.text = finishText;
		else
			OnButtonNextLabel.text = nextText;
	}
}
