using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class TutorialController : WindowController {
	
	public enum TutorialIndex{
		AddPain,
		ZoomArea,
		TabArea,
		RateArea
	}

	[SerializeField] TutorialIndex index;

	public void OnButtonNext(){
		if(index == TutorialIndex.AddPain)
			stateMachine.MoveToSelected(WindowPanels.TutorialZoomArea);
		else if(index == TutorialIndex.ZoomArea)
			stateMachine.MoveToSelected(WindowPanels.TutorialTabArea);
		else if(index == TutorialIndex.TabArea)
			stateMachine.MoveToSelected(WindowPanels.TutorialRateArea);
		else if(index == TutorialIndex.RateArea)
			stateMachine.MoveToSelected(WindowPanels.PainSelector);
	}

	public void SkipTutorial(){
		stateMachine.MoveToSelected(WindowPanels.PainSelector);
	}

	public void OnButtonBack(){
		if(index == TutorialIndex.ZoomArea)
			stateMachine.MoveToSelected(WindowPanels.TutorialAddPain);
		else if(index == TutorialIndex.TabArea)
			stateMachine.MoveToSelected(WindowPanels.TutorialZoomArea);
		else if(index == TutorialIndex.RateArea)
			stateMachine.MoveToSelected(WindowPanels.TutorialTabArea);
	}

}
