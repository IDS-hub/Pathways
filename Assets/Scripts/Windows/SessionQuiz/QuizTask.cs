using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class QuizTask {
	public string Question { get; set; }

	public bool CorrectAnswer { get; set; }

	public string Annotation { get; set; }

	public QuizTask(string question, bool correct, string annotation) {
		Question = question;
		CorrectAnswer = correct;
		Annotation = annotation;
	}
}
